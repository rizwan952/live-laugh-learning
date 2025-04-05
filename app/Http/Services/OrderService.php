<?php

namespace App\Http\Services;

use App\Http\Resources\OrderResource;
use App\Models\CoursePackage;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\OrderPackageLesson;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class OrderService
{

    public function getOrders(Request $request)
    {
        $query = Order::where('student_id', $request->user()->id);

        // Check if order_id is provided and filter accordingly
        if ($request->has('orderId')) {
            $query->where('id', $request->orderId);
        }

        $orders = $query->get();
        return OrderResource::collection($orders);
    }

    public function createOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            $package = CoursePackage::find($request->packageId);

            $order = Order::create([
                'student_id' => $request->user()->id,
                'course_id' => $package->duration->course_id,
                'course_name' => $package->duration->course->name,
                'course_price' => $package->price,
                'final_amount' => $package->price
            ]);
            $orderPackage = OrderPackage::create([
                'order_id' => $order->id,
                'duration_id' => $package->duration->id,
                'package_id' => $package->id,
                'duration' => $package->duration->duration,
                'type' => $package->type,
                'price' => $package->price,
                'lesson_count' => $package->lesson_count
            ]);

            // Prepare data for bulk insert
            $eachLessonPrice = $package->price / $package->lesson_count;
            $lessonData = [];
            for ($i = 0; $i < $package->lesson_count; $i++) {
                $lessonData[] = [
                    'order_id' => $order->id,
                    'order_package_id' => $orderPackage->id,
                    'amount' => $eachLessonPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert into OrderPackageLesson table
            OrderPackageLesson::insert($lessonData);

            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $order->final_amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            $order->update([
                'payment_method' => 'stripe',
                'payment_id' => $paymentIntent->id,
                'payment_details' => json_encode($paymentIntent)
            ]);

            DB::commit();

            return [
                'orderId' => $order->id,
                'paymentIntent' => $paymentIntent->client_secret
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateOrderLessons(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            // Get the packageLessons data from the request
            $packageLessons = $request->packageLessons;
            // Update each lesson
            foreach ($packageLessons as $lessonData) {
                $lesson = OrderPackageLesson::where('id', $lessonData['id'])
                    ->where('order_id', $order->id) // Ensure the lesson belongs to this order
                    ->first();

                if (!$lesson) {
                    throw new Exception("Lesson with ID {$lessonData['id']} not found or does not belong to this order");
                }

                // Update the lesson with only the provided fields
                $lesson->update([
                    'start_at' => $lessonData['startAt'],
                    'end_at' => $lessonData['endAt'],
                    'time_zone' => $lessonData['timeZone'],
                    'status' => 'processing'
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function initiateRefund(Request $request, OrderPackageLesson $orderPackageLesson)
    {
        try {
            DB::beginTransaction();

            if ($orderPackageLesson->order->student->id != auth()->id()) {
                throw new Exception("OrderLesson with ID {$orderPackageLesson->id} not found or does not belong to this student");
            }
            $orderPackageLesson->update([
                'status' => 'refund_initiated',
                'reason' => $request->reason
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function handleWebhook(Request $request)
    {
        Log::info('Inside handleWebhook');
        Stripe::setApiKey(config('services.stripe.secret'));
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret'); // Set this in .env

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Inside handleWebhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Inside handleWebhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event based on its type using if-else
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $paymentStatus = 'completed';
            Order::where('payment_id', $paymentIntent->id)->update([
                'payment_status' => $paymentStatus,
                'payment_details' => json_encode($paymentIntent)
            ]);
            Log::info('Payment succeeded for ' . $paymentIntent->id);
        } else if ($event->type === 'payment_intent.payment_failed' || $event->type === 'payment_intent.canceled') {
            $paymentIntent = $event->data->object;
            $paymentStatus = 'failed';
            Order::where('payment_id', $paymentIntent->id)->update([
                'payment_status' => $paymentStatus,
                'payment_details' => json_encode($paymentIntent)
            ]);
            Log::info('Payment failed/canceled for ' . $paymentIntent->id);

        } else if ($event->type === 'refund.updated') {
            $refund = $event->data->object;
            $lesson = OrderPackageLesson::where('refund_id', $refund->id)->first();

            if ($lesson) {
                $refundedAt = null;
                if ($refund->status === 'succeeded') {
                    $status = 'refunded';
                    $refundedAt = now();
                } else if ($refund->status === 'failed') {
                    $status = 'refund_failed';
                } else if ($refund->status === 'pending') {
                    $status = 'refund_processing';
                } else {
                    $status = $lesson->status;
                }

                $lesson->update([
                    'status' => $status,
                    'refunded_at' => $refundedAt,
                    'refund_details' => json_encode([
                        'stripe_status' => $refund->status,
                        'amount' => $refund->amount / 100,
                        'reason' => $refund->reason,
                        'failure_reason' => $refund->failure_reason ?? null,
                        'updated_at' => now()->toDateTimeString()
                    ])
                ]);
                // Update the order lesson in case of refund succeeded
                if($refund->status === 'succeeded'){
                    // Check if the all lessons of this course are refunded
                    $lessons = OrderPackageLesson::where('order_id', $lesson->order_id)->get();
                    // Count refund statuses
                    $totalLessons = $lessons->count();
                    $refundedLessons = $lessons->where('status', 'refunded')->count();
                    $newStatus = 'partially_refunded';
                    if ($refundedLessons === $totalLessons) {
                        // All lessons are refunded
                        $newStatus = 'refunded';
                    }
                    $lesson->order->update([
                        'status' => $newStatus,
                        'refund_amount' => $lesson->amount,
                        'final_amount' =>  $lesson->order->final_amount - $refund->amount,
                    ]);

                }

                Log::info('Refund updated for lesson ' . $lesson->id . ' with refund ID ' . $refund->id);
            }
        } else {
            Log::info('Unhandled event type: ' . $event->type);
        }
        return response()->json(['message' => 'Webhook received'], 200);
    }
}
