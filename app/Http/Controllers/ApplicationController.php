<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TutorApprovedMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\TutorRejectedMail;



class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $tab = $request->tab;

    //     $students = User::where('role', 'student');

    //     if ($tab == 'Approve') {
    //         $students = $students->where('status', 'approved');
    //     } elseif ($tab == 'reject') {
    //         $students = $students->where('status', 'rejected');
    //     } else {
    //         $students = $students->where('status', 'pending'); // default for New tab
    //     }

    //     $students = $students->get();

    //     $data = [
    //         'students' => $students,
    //         'tab' => $tab
    //     ];

    //     return view('admin.list-application-student', $data);

    // }

    public function approveStudent($id)
    {
        $user = User::where('id', $id)->where('role', 'student')->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        $user->status = 'approved';
        $user->save();

        return redirect()->back()->with('success', 'Student approved successfully!');
    }


    public function rejectStudent(int $id)
    {
        $user = User::where('id', $id)->where('role', 'student')->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        $user->status = 'rejected';
        $user->save();

        return redirect()->back()->with('success', 'Student reject successfully!');
    }

    public function indexTutor(Request $request)
    {
        $pendingTutors = User::where('role', 'Tutor')->where('status', 'pending')->with('tutorProfile', 'subjects')->get();
        $approvedTutors = User::where('role', 'Tutor')->where('status', 'approved')->with('tutorProfile', 'subjects')->get();
        $rejectedTutors = User::where('role', 'Tutor')->where('status', 'rejected')->with('tutorProfile', 'subjects')->get();

        $data = [
            'pendingTutors' => $pendingTutors,
            'approvedTutors' => $approvedTutors,
            'rejectedTutors' => $rejectedTutors,
        ];

        return view('admin.list-application-tutor', $data);
    }

    public function showTutorApplication(int $id)
    {
        $tutor = User::where('id', $id)
            ->where('role', 'Tutor')
            ->with(['tutorProfile.educationLevel', 'subjects'])
            ->firstOrFail();

        return view('admin.show-tutor-application', compact('tutor'));
    }

    public function approveTutor(int $id)
    {
        $user = User::with('subjects')->where('id', $id)->where('role', 'Tutor')->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Tutor not found.');
        }

        $tempPassword = str_replace(' ', '', $user->name) . '03';

        $user->status = 'approved';
        $user->password = Hash::make($tempPassword);
        $user->save();

        // Get the names of the subjects they are assigned to
        $subjectNames = $user->subjects->pluck('name')->toArray();

        try {
            // Pass $subjectNames to the Mail constructor
            Mail::to($user->email)->send(new TutorApprovedMail($user, $tempPassword, $subjectNames));
        } catch (\Exception $e) {
            Log::error("Mail failed: " . $e->getMessage());
        }

        Alert::success('Tutor Approved', 'Credentials and subject assignments sent.');
        return redirect()->route('application.indexTutor', ['tab' => 'Approve']);
    }


    public function rejectTutor(int $id)
    {
        $user = User::findOrFail($id);

        // 1. Update Status and Timestamp
        $user->status = 'rejected';
        $user->rejected_at = now();
        $user->save();

        // 2. Send the Rejection Email
        try {
            Mail::to($user->email)->send(new TutorRejectedMail($user));
        } catch (\Exception $e) {
            Log::error("Rejection Mail failed: " . $e->getMessage());
        }

        Alert::success('Rejected', 'Candidate has been notified via email.');
        return redirect()->route('application.indexTutor', ['tab' => 'Approve']);
    }

    public function indexEnrollment(Request $request)
    {
        $tab = $request->tab;


        $enrollments = Enrollment::with(['student', 'class.subject', 'tutor']);

        if ($tab == 'Approve') {
            $enrollments = $enrollments->where('status', 'approved');
        } elseif ($tab == 'reject') {
            $enrollments = $enrollments->where('status', 'rejected');
        } else {
            $enrollments = $enrollments->where('status', 'pending'); // Default: New tab
        }

        $enrollments = $enrollments->get();

        $data = [
            'enrollments' => $enrollments,
            'tab' => $tab
        ];

        return view('admin.list-application-enrollment', $data);
    }

    public function tutorProfile( int $tutor_id)
    {
        // Fetch class with its relations
    //    $class = CreateClass::with(['schedules', 'subject'])->findOrFail($class_id);

        // Fetch tutor with their AI profile data
        $tutor = User::with('tutorProfile')->findOrFail($tutor_id);

        $data =[
            // 'class' => $class,
            'tutor' => $tutor

        ];

        return view('admin.tutor-profile', $data);
    }

    // public function approveEnrollment(int $id)
    // {
    //     $enrollment = Enrollment::findOrFail($id);

    //     $enrollment->status = 'approved';
    //     $enrollment->save();

    //     return redirect()->back()->with('success', 'Class enrollment approved successfully!');
    // }

    // public function rejectEnrollment(int $id)
    // {
    //     $enrollment = Enrollment::findOrFail($id);

    //     $enrollment->status = 'rejected';
    //     $enrollment->save();

    //     return redirect()->back()->with('success', 'Class enrollment rejected successfully!');
    // }

    public function indexTutorChanges()
    {
        // Fetch all pending enrollments
        $pending = Enrollment::where('status', 'pending')->with(['student', 'class.subject', 'tutor'])->get();

        // Filter only those that are actually "Change Requests"
        // (Student already has an approved record for this class/subject)
        $changeRequests = $pending->filter(function ($enrollment) {
            return Enrollment::where('student_id', $enrollment->student_id)
                ->where('class_id', $enrollment->class_id)
                ->where('status', 'approved')
                ->exists();
        });

        return view('admin.tutor_changes', compact('changeRequests'));
    }
}
