<?php

namespace App\Http\Services;

use App\Http\Resources\OrderResource;
use App\Models\CoursePackage;
use App\Models\Order;
use App\Models\OrderPackage;
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

    public function getOrders()
    {
        $orders = Order::all();
        return OrderResource::collection($orders);
    }

    public function createOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            $package = CoursePackage::find($request->package_id);

            $order = Order::create([
                'student_id' => $request->user()->id,
                'course_id' => $package->duration->course_id,
                'course_name' => $package->duration->course->name,
                'course_price' => $package->price,
                'final_amount' => $package->price
            ]);
            OrderPackage::create([
                'order_id' => $order->id,
                'duration_id' => $package->duration->id,
                'package_id' => $package->id,
                'duration' => $package->duration->duration,
                'type' => $package->type,
                'price' => $package->price,
            ]);

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

            return $paymentIntent->client_secret;
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

        $paymentIntent = $event->data->object;
        $paymentStatus = 'processing';
        if ($event->type === 'payment_intent.succeeded') {
           $paymentStatus = 'completed';
        }elseif ($event->type === 'payment_intent.payment_failed' || $event->type === 'payment_intent.canceled') {
            $paymentStatus = 'failed';
        }
        // Update payment status in database
        Order::where('payment_id', $paymentIntent->id)->update(['payment_status' => $paymentStatus, 'payment_details' => json_encode($paymentIntent)]);
        Log::info('Webhook Handled  payment event for '.$paymentIntent->id);
        Log::info('Webhook Handled Event Type '.$event->type);
        return response()->json(['message' => 'Webhook received'], 200);
    }
}
