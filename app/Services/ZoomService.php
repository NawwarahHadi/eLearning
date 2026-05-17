<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    /**
     * Authenticates with Zoom and retrieves a temporary bearer token
     */
    protected function getAccessToken(): ?string
    {
        $accountId    = config('services.zoom.account_id');
        $clientId     = config('services.zoom.client_id');
        $clientSecret = config('services.zoom.client_secret');

        try {
            $response = Http::asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post("https://zoom.us/oauth/token", [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId,
                ]);

            if ($response->failed()) {
                Log::error('Zoom OAuth Token Request Failed: ' . $response->body());
                return null;
            }

            return $response->json('access_token');
        } catch (\Exception $e) {
            Log::error('Zoom Authentication Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Communicates with Zoom API to automatically spawn a dynamic meeting room instance
     */
    /**
     * Talks to the Zoom API to automatically create a meeting link
     */
    public function createMeeting(string $topic, string $startTimeMinutes, int $durationMinutes = 60): ?string
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        try {
            // Reformat time string payload into standard ISO 8601 format required by Zoom API
            $formattedStartTime = date('Y-m-d\TH:i:s', strtotime($startTimeMinutes));

            // 🌟 FIX: Added 'api.' to the subdomain URL line below to match your working example!
            $response = Http::withToken($token)
                ->post("https://api.zoom.us/v2/users/me/meetings", [
                    'topic'      => $topic,
                    'type'       => 2,
                    'start_time' => $formattedStartTime,
                    'duration'   => $durationMinutes,
                    'timezone'   => 'Asia/Kuala_Lumpur',
                    'settings'   => [
                        'host_video'        => true,
                        'participant_video' => true,
                        'join_before_host'  => true,
                        'mute_upon_entry'   => true,
                        'waiting_room'      => false,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Zoom Meeting Generation Failed: ' . $response->body());
                return null;
            }

            // Return the direct web connection link pointer response back to the handler pipeline
            return $response->json('join_url');
        } catch (\Exception $e) {
            Log::error('Zoom Meeting Creation Exception: ' . $e->getMessage());
            return null;
        }
    }
}
