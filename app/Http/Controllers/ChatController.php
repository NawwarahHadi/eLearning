<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Models\CreateClass;
use App\Models\RescheduleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    public function index()
    {
        $myId = Auth::id();

        $users = User::where('id', '!=', $myId)
            ->with(['tutorProfile', 'studentProfile'])
            ->get()
            ->map(function ($user) use ($myId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $myId)
                    ->where('is_read', false)
                    ->count();
                return $user;
            });

        return view('messenger', [
            'users'    => $users,
            'receiver' => null,          // nobody selected yet
            'messages' => collect(),
            'classes'  => collect(),
        ]);
    }


    public function store(Request $request)
    {
        // 1. Force save the message to the messages table
        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
            'is_read'     => 0
        ]);

        // 2. Logic for the RescheduleRequest table
        if (str_contains($request->message, '[RESCHEDULE_REQUEST]')) {
            $details = json_decode(str_replace('[RESCHEDULE_REQUEST]', '', $request->message), true);
            if ($details) {
                RescheduleRequest::create([
                    'class_id'      => $details['class_id'],
                    'student_id'    => Auth::id(),
                    'tutor_id'      => $request->receiver_id,
                    'reason'        => $details['reason'],
                    'proposed_time' => $details['time'],
                    'status'        => 'pending'
                ]);
            }
        }

        broadcast(new \App\Events\MessageSent($message))->toOthers();
        return response()->json(['status' => 'success']);
    }

    public function show(int $receiver_id)
    {
        $myId = Auth::id();

        // Mark all messages FROM this person TO me as read
        Message::where('sender_id', $receiver_id)
            ->where('receiver_id', $myId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $receiver = User::with(['tutorProfile', 'studentProfile'])->findOrFail($receiver_id);

        // Contacts for the left pane, each with its unread count
        $users = User::where('id', '!=', $myId)
            ->with(['tutorProfile', 'studentProfile'])
            ->get()
            ->map(function ($user) use ($myId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $myId)
                    ->where('is_read', false)
                    ->count();
                return $user;
            });

        // Conversation history between the two users
        $messages = Message::where(function ($q) use ($myId, $receiver_id) {
                $q->where('sender_id', $myId)->where('receiver_id', $receiver_id);
            })
            ->orWhere(function ($q) use ($myId, $receiver_id) {
                $q->where('sender_id', $receiver_id)->where('receiver_id', $myId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Shared classes between student and tutor (for the reschedule modal)
        $classes = CreateClass::where(function ($q) use ($myId, $receiver_id) {
                $q->where('tutor_id', $receiver_id)->orWhere('tutor_id', $myId);
            })
            ->with('subject')
            ->get();

        return view('messenger', compact('receiver', 'messages', 'classes', 'users'));
    }


    public function getUnreadCount()
    {
        // Count messages where the receiver is ME and is_read is false
        return Message::where('receiver_id',auth::id())
                    ->where('is_read', false)
                    ->count();
    }

    public function markRead(int $sender_id)
    {
        Message::where('sender_id', $sender_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
