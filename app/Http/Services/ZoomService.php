<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    protected $accountId;
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->accountId = config('services.zoom.account_id');
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
        $this->baseUrl = config('services.zoom.api_base_url');
    }

    /**
     * Get Zoom access token with caching
     */
    public function getAccessToken()
    {
        return Cache::remember('zoom_access_token', now()->addMinutes(50), function () {
            Log::info('Fetching new Zoom access token');
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if ($response->failed()) {
                Log::error('Failed to get Zoom access token', ['error' => $response->json()]);
                throw new \Exception('Failed to get Zoom access token: ' . $response->body());
            }

            return $response->json()['access_token'];
        });
    }

    /**
     * Create a single Zoom meeting
     */
    public function createMeeting($startTime, $duration, $courseName,$timezone)
    {
        $accessToken = $this->getAccessToken();
        $meetingData = [
            'topic' => $courseName ? "{$courseName} - 1:1 with Student" : '1:1 Course Meeting',
            'type' => 2, // Scheduled meeting
            'start_time' => $startTime, // e.g., "2025-05-26T14:00:00Z"
            'duration' => $duration, // in minutes
            'timezone' => $timezone,
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => true,
                'mute_upon_entry' => true,
                'waiting_room' => true,
                'approval_type' => 2, // No registration required
                'registrants_email_notification' => false,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/users/me/meetings", $meetingData);

        if ($response->failed()) {
            Log::error('Failed to create Zoom meeting', ['error' => $response->json()]);
            throw new \Exception('Failed to create Zoom meeting: ' . $response->body());
        }

        Log::info('Zoom meeting created', ['meeting_id' => $response->json()]);
        return $response->json();
    }

    /**
     * Create multiple Zoom meetings in batch
     */
    public function createMultipleMeetings($bookings)
    {
        $accessToken = $this->getAccessToken();
        $results = [];

        foreach ($bookings as $booking) {
            try {
                $meetingData = [
                    'topic' => $booking['course_name'] ? "{$booking['course_name']} - 1:1 with Student" : '1:1 Course Meeting',
                    'type' => 2,
                    'start_time' => $booking['start_time'],
                    'duration' => $booking['duration'],
                    'timezone' => 'UTC',
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => false,
                        'mute_upon_entry' => true,
                        'waiting_room' => true,
                        'approval_type' => 2,
                        'registrants_email_notification' => false,
                    ],
                ];

                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                ])->post("{$this->baseUrl}/users/me/meetings", $meetingData);

                if ($response->failed()) {
                    Log::error('Failed to create Zoom meeting for booking', [
                        'booking' => $booking,
                        'error' => $response->json()
                    ]);
                    $results[] = [
                        'booking' => $booking,
                        'status' => 'failed',
                        'error' => $response->json() ?: 'Unknown error',
                    ];
                    continue;
                }

                $results[] = [
                    'booking' => $booking,
                    'status' => 'success',
                    'meeting_id' => $response->json()['id'],
                    'join_url' => $response->json()['join_url'],
                    'start_time' => $response->json()['start_time'],
                ];
            } catch (\Exception $e) {
                Log::error('Exception creating Zoom meeting for booking', [
                    'booking' => $booking,
                    'error' => $e->getMessage()
                ]);
                $results[] = [
                    'booking' => $booking,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
