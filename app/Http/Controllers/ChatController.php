<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Models\RescheduleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // If you are a Student, you want to see Tutors.
        // If you are a Tutor, you want to see Students.
        // For now, let's just get all users except yourself:
        $users = User::where('id', '!=', Auth::id())->get();

        // We also want to get the unread count for the notification badge
        $unreadCount = Message::where('receiver_id', Auth::id())
                            ->where('is_read', false)
                            ->count();

        return view('chat.index', compact('users', 'unreadCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'receiver_id' => 'required|integer',
    //         'message' => 'required|string',
    //     ]);

    //     $message = Message::create([
    //         'sender_id' => Auth::id(),
    //         'receiver_id' => $request->receiver_id,
    //         'message' => $request->message,
    //     ]);

    //     // 1. Remove .toOthers() for now to make testing easier
    //     // 2. We will make this "sync" in the next step so it doesn't need the queue
    //     broadcast(new MessageSent($message));

    //     // 3. IMPORTANT: Return JSON, not back() for Axios calls
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => $message
    //     ]);
    //     //  return back();
    // }

    // public function show(int $receiver_id)
    // {
    //     $receiver = User::findOrFail($receiver_id);
    //     $sender_id = Auth::id();

    //     // Fetch messages between these two users
    //     $messages = Message::where(function($q) use ($sender_id, $receiver_id) {
    //         $q->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
    //     })->orWhere(function($q) use ($sender_id, $receiver_id) {
    //         $q->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
    //     })->orderBy('created_at', 'asc')->get();

    //     return view('messenger', compact('receiver', 'messages'));
    // }

    // Inside ChatController -> store() method
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
            \App\Models\RescheduleRequest::create([
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
        $receiver = User::findOrFail($receiver_id);
        $sender_id = Auth::id();

        // Fetch message history records between these two specific primary keys
        $messages = Message::where(function($q) use ($sender_id, $receiver_id) {
            $q->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
        })->orWhere(function($q) use ($sender_id, $receiver_id) {
            $q->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
        })->orderBy('created_at', 'asc')->get();

        // 🌟 Fetch active classes shared between this student and tutor
        // Maps both directions so it loads fluidly whether student or tutor views the view panel
        $classes = \App\Models\CreateClass::where(function($q) use ($sender_id, $receiver_id) {
                $q->where('tutor_id', $receiver_id)
                ->orWhere('tutor_id', $sender_id);
            })
            ->with('subject')
            ->get();

        return view('messenger', compact('receiver', 'messages', 'classes'));
    }


    public function getUnreadCount()
    {
        // Count messages where the receiver is ME and is_read is false
        return Message::where('receiver_id',auth::id())
                    ->where('is_read', false)
                    ->count();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
