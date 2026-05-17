<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\ClassSchedule;
use App\Models\RescheduleRequest;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
// 🌟 1. IMPORT YOUR ZOOM SERVICE HERE
use App\Services\ZoomService;

class SchedullingController extends Controller
{
    // 🌟 2. INJECT THE ZOOMSERVICE CLASS INTO THIS METHOD
    // public function approveReschedule(Request $request, ZoomService $zoomService)
    // {
    //     $request->validate([
    //         'message_id' => 'required|exists:messages,id',
    //     ]);

    //     $message = Message::findOrFail($request->message_id);

    //     if ($message->receiver_id !== Auth::id()) {
    //         return response()->json(['error' => 'Unauthorized action.'], 403);
    //     }

    //     $rawText = $message->message;
    //     $cleanJson = str_replace('[RESCHEDULE_REQUEST]', '', $rawText);
    //     $details = json_decode($cleanJson, true);

    //     if (!$details) {
    //         return response()->json(['error' => 'Invalid data format.'], 422);
    //     }

    //     // Extract day string dynamically
    //     $proposedDay = date('l', strtotime($details['time']));

    //     // 🌟 3. AUTOMATION HOOK: Run the API call to build a genuine Zoom room
    //     $subjectName = $details['class_name'] ?? 'Makeup Class';
    //     $meetingTopic = "1-to-1 Rescheduled Session: " . $subjectName;

    //     $automatedZoomLink = $zoomService->createMeeting($meetingTopic, $details['time'], 60);
    //     $calculatedEndTime = date('Y-m-d H:i', strtotime('+60 minutes', strtotime($details['time'])));
    //     // Fallback placeholder if your Zoom API keys are empty during testing
    //     if (!$automatedZoomLink) {
    //         $automatedZoomLink = 'https://zoom.us/j/fallback-placeholder-room';
    //     }

    //     // 1. Spawns the private 1-to-1 temporary makeup class row using the real automated Zoom URL!
    //     ClassSchedule::create([
    //         'class_id'              => $details['class_id'],
    //        'end_time'              => $calculatedEndTime,
    //         'start_time'            => $details['time'],
    //         'day'                   => $proposedDay,
    //         'is_temporary'          => true,
    //         'reschedule_student_id' => $message->sender_id,
    //         'reschedule_reason'     => $details['reason'],
    //         'zoom_link'             => $automatedZoomLink // 🌟 FIX: SAVES THE AUTOMATED LINK
    //     ]);

    //     RescheduleRequest::where('class_id', $details['class_id'])
    //         ->where('student_id', $message->sender_id)
    //         ->where('status', 'pending')
    //         ->update(['status' => 'approved']);

    //     // 2. Mutate the status tag string token
    //     $message->update([
    //         'message' => '[RESCHEDULE_RESOLVED_APPROVED]' . $cleanJson
    //     ]);

    //     // 3. Broadcast state change over WebSockets
    //     broadcast(new \App\Events\MessageSent($message))->toOthers();

    //     return response()->json(['status' => 'success', 'message' => 'Approved!']);
    // }
    public function approveReschedule(Request $request)
{
    $request->validate([
        'message_id' => 'required|exists:messages,id',
    ]);

    $message = Message::findOrFail($request->message_id);

    if ($message->receiver_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized action.'], 403);
    }

    $rawText = $message->message;
    $cleanJson = str_replace('[RESCHEDULE_REQUEST]', '', $rawText);
    $details = json_decode($cleanJson, true);

    if (!$details) {
        return response()->json(['error' => 'Invalid data format.'], 422);
    }

    // 1. Format the date-time string exactly for Zoom's ISO 8601 requirement
    // Converts "2026-05-17 14:00" into "2026-05-17T14:00:00"
    $formattedStartTime = date('Y-m-d\TH:i:s', strtotime($details['time']));
    $proposedDay = date('l', strtotime($details['time']));
    $calculatedEndTime = date('Y-m-d H:i', strtotime('+60 minutes', strtotime($details['time'])));

    $automatedZoomLink = null;

    // 🌟 CONNECT DIRECTLY TO ZOOM USING YOUR WORKING METHOD HOOKS
    try {
        $responseToken = \Illuminate\Support\Facades\Http::asForm()
            ->withBasicAuth(config('services.zoom.client_id'), config('services.zoom.client_secret'))
            ->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id=" . config('services.zoom.account_id'));

        if ($responseToken->successful()) {
            $token = $responseToken->json()['access_token'];

            $subjectName = $details['class_name'] ?? 'Makeup Class';
            $responseMeeting = \Illuminate\Support\Facades\Http::withToken($token)
                ->post('https://api.zoom.us/v2/users/me/meetings', [
                    'topic'      => "1-to-1 Rescheduled: " . $subjectName,
                    'type'       => 2, // Scheduled meeting
                    'start_time' => $formattedStartTime, // Use the real requested student time payload
                    'duration'   => 60, // 1 hour duration
                    'timezone'   => 'Asia/Kuala_Lumpur',
                    'settings'   => [
                        'host_video'        => true,
                        'participant_video' => true,
                        'join_before_host'  => true,
                        'mute_upon_entry'   => true,
                        'waiting_room'      => false,
                    ]
                ]);

            if ($responseMeeting->successful()) {
                $meeting = $responseMeeting->json();
                $automatedZoomLink = $meeting['join_url']; // Grab the active string payload link
            } else {
                \Illuminate\Support\Facades\Log::error('Zoom Meeting Creation Failed Payload: ' . $responseMeeting->body());
            }
        } else {
            \Illuminate\Support\Facades\Log::error('Zoom Token Exchange Failed Payload: ' . $responseToken->body());
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Zoom Direct Inline Crash Exception: ' . $e->getMessage());
    }

    // Secure fallback string so the database insert statement doesn't crash if credentials drop out
    if (!$automatedZoomLink) {
        $automatedZoomLink = 'https://zoom.us/j/fallback-placeholder-room';
    }

    // 2. Insert the complete dataset into your class_schedules table engine map
    ClassSchedule::create([
        'class_id'              => $details['class_id'],
        // 'tutor_id'              => Auth::id(),
        'start_time'            => $details['time'],
        'end_time'              => $calculatedEndTime,
        'day'                   => $proposedDay,
        'is_temporary'          => true,
        'reschedule_student_id' => $message->sender_id,
        'reschedule_reason'     => $details['reason'],
        'zoom_link'             => $automatedZoomLink // 🌟 Populates your target row entry column here!
    ]);

    // 3. Complete structural log and state synchronization
    RescheduleRequest::where('class_id', $details['class_id'])
        ->where('student_id', $message->sender_id)
        ->where('status', 'pending')
        ->update(['status' => 'approved']);

    $message->update([
        'message' => '[RESCHEDULE_RESOLVED_APPROVED]' . $cleanJson
    ]);

    broadcast(new \App\Events\MessageSent($message))->toOthers();

    return response()->json(['status' => 'success', 'message' => 'Approved successfully!']);
}
    public function rejectReschedule(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id'
        ]);

        $message = Message::findOrFail($request->message_id);

        if ($message->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $cleanJson = str_replace('[RESCHEDULE_REQUEST]', '', $message->message);
        $details = json_decode($cleanJson, true);

        if ($details) {
            RescheduleRequest::where('class_id', $details['class_id'])
                ->where('student_id', $message->sender_id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        }

        $message->update([
            'message' => '[RESCHEDULE_RESOLVED_REJECTED]' . $cleanJson
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json(['status' => 'success', 'message' => 'Rejected successfully.']);
    }
}
