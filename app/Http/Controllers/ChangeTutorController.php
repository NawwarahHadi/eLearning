<?php

namespace App\Http\Controllers;

use App\Models\CreateClass;
use App\Models\Enrollment;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeTutorController extends Controller
{
    // ─────────────────────────────────────────
    // Show OTHER tutors teaching the same subject
    // ─────────────────────────────────────────
    public function changeTutorSelection( $class_id)
    {
        $currentClass = CreateClass::with(['subject', 'tutor'])->findOrFail($class_id);

        $availableClasses = CreateClass::with(['subject', 'tutor.tutorProfile', 'schedules'])
            ->where('subject_id', $currentClass->subject_id)
            ->where('id', '!=', $class_id)
            ->get();

        return view('change-tutor.index', compact('currentClass', 'availableClasses'));
    }

    // ─────────────────────────────────────────
    // Show selected tutor's profile + request form
    // ─────────────────────────────────────────
    public function tutorProfile( $class_id,  $old_class_id)
    {
        $class = CreateClass::with(['subject', 'tutor.tutorProfile', 'schedules'])
            ->findOrFail($class_id);

        $tutor     = $class->tutor;
        $oldClassId = $old_class_id;

        return view('change-tutor.profile', compact('class', 'tutor', 'oldClassId'));
    }

    // ─────────────────────────────────────────
    // Student requests tutor change (pending)
    // ─────────────────────────────────────────
    public function requestTutorChange(Request $request)
    {
        $request->validate([
            'old_class_id'  => 'required|exists:class,id',
            'new_class_id'  => 'required|exists:class,id',
            'tutor_id'      => 'required|exists:users,id',
            'schedule_id'   => 'required|array|min:1',
            'schedule_id.*' => 'exists:class_schedules,id',
        ]);

        $student  = Auth::user();
        $newClass = CreateClass::findOrFail($request->new_class_id);

        // Prevent duplicate pending/approved request for this class
        $existing = Enrollment::where('student_id', $student->id)
            ->where('class_id', $request->new_class_id)
            ->whereIn('status', ['pending', 'approve'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'You already requested or enrolled with this tutor.');
        }

        // Create one pending enrollment per selected slot
        foreach ($request->schedule_id as $scheduleId) {
            Enrollment::create([
                'student_id'  => $student->id,
                'class_id'    => $request->new_class_id,
                'tutor_id'    => $newClass->tutor_id,
                'schedule_id' => $scheduleId,
                'status'      => 'pending',
            ]);
        }

        return redirect()->route('student.class.index')
            ->with('success', 'Tutor change request submitted. Waiting for admin approval.');
    }


    // ─────────────────────────────────────────
    // Admin approves (by student + class) + notify tutor
    // ─────────────────────────────────────────
    public function approveChange(Request $request, int $student_id, int $class_id)
    {
        $newClass = CreateClass::with('subject')->findOrFail($class_id);
        $student  = User::findOrFail($student_id);

        // Reject old approved enrollment(s) for same subject, different class
        Enrollment::whereHas('class', function ($q) use ($newClass) {
                $q->where('subject_id', $newClass->subject_id);
            })
            ->where('student_id', $student_id)
            ->where('status', 'approve')
            ->where('class_id', '!=', $class_id)
            ->update(['status' => 'rejected']);

        // Approve all pending enrollments for the new class
        Enrollment::where('student_id', $student_id)
            ->where('class_id', $class_id)
            ->where('status', 'pending')
            ->update(['status' => 'approve']);

        // Notify the NEW tutor via chat
        $subjectName = $newClass->subject->name ?? 'a subject';

        $notification = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $newClass->tutor_id,
            'message'     => "📢 New Student Assigned: {$student->name} has been approved to join your {$subjectName} class. Please check your class list.",
            'is_read'     => 0,
        ]);

        broadcast(new \App\Events\MessageSent($notification))->toOthers();

        return back()->with('success', 'Tutor change approved. The new tutor has been notified.');
    }

    // ─────────────────────────────────────────
    // Admin rejects the change request
    // ─────────────────────────────────────────
    public function rejectChange(Request $request, int $student_id, int $class_id)
    {
        Enrollment::where('student_id', $student_id)
            ->where('class_id', $class_id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return back()->with('success', 'Tutor change request rejected.');
    }

    public function pendingRequests()
    {
        $pendingEnrollments = Enrollment::with([
                'student', 'tutor.tutorProfile', 'class.subject', 'schedule',
            ])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn($e) => $e->student_id . '-' . $e->class_id);

        return view('change-tutor.admin-requests', compact('pendingEnrollments'));
    }
}
