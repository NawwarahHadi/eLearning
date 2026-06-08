<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\CreateClass;
use App\Models\Enrollment;
use App\Models\Evaluation;
use App\Models\Feedback;
use App\Models\LearningMaterial;
use App\Models\Quiz;
use App\Models\RescheduleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Static stats for top cards
        $stats = [
            'total_students' => 1250,
            'pending_apps' => 45,
            'revenue' => 15250.00,
            'rejected_apps' => 12
        ];

        return view('dashboardAdmin', compact('stats'));
    }

    public function indexTutor()
    {
        $tutorId = Auth::id();

        $stats = [
            'total_classes'  => CreateClass::where('tutor_id', $tutorId)->count(),
            'total_students' => Enrollment::where('tutor_id', $tutorId)
                                    ->distinct('student_id')->count('student_id'),
            'avg_rating'     => round(Feedback::where('tutor_id', $tutorId)->avg('rating') ?? 0, 1),
            'total_quizzes'  => Quiz::where('tutor_id', $tutorId)->count(),
        ];

        $pendingReschedules = RescheduleRequest::where('tutor_id', $tutorId)
            ->where('status', 'pending')
            ->with(['classModule.subject', 'student'])
            ->orderBy('proposed_time', 'asc')
            ->get();

        $recentFeedback = Feedback::where('tutor_id', $tutorId)
            ->with(['student', 'class.subject'])
            ->latest()->take(5)->get();

        $myClasses = CreateClass::where('tutor_id', $tutorId)
            ->withCount('students')->with('subject')->latest()->take(5)->get();

        $avgEvaluation = round(Evaluation::where('tutor_id', $tutorId)->avg('overall_score') ?? 0, 2);

        // Today's schedule (recurring, by weekday name)
        $todayName = now()->format('l');
        $todayDate = now()->toDateString();

        $todaySchedule = \App\Models\ClassSchedule::where('day', $todayName)
            ->where('is_temporary', 0)
            ->whereHas('classModule', fn($q) => $q->where('tutor_id', $tutorId))
            ->with('classModule.subject')
            ->orderBy('start_time')
            ->get()
            ->map(function ($sched) use ($todayDate) {
                $sched->todayMaterial = \App\Models\LearningMaterial::where('class_id', $sched->class_id)
                    ->whereDate('class_date', $todayDate)
                    ->first();
                return $sched;
            });
        return view('dashboardTutor', compact(
            'stats', 'pendingReschedules', 'recentFeedback',
            'myClasses', 'avgEvaluation', 'todaySchedule'
        ));
    }

    public function indexStudent()
    {
        $studentId = \Illuminate\Support\Facades\Auth::id();

        // Real stats
        $stats = [
            'enrolled_classes' => \App\Models\Enrollment::where('student_id', $studentId)
                                    ->where('status', 'approved')
                                    ->distinct('class_id')->count('class_id'),
            'quizzes_taken'    => \App\Models\QuizAttempt::where('student_id', $studentId)->count(),
            'avg_quiz'         => round(\App\Models\QuizAttempt::where('student_id', $studentId)->avg('score') ?? 0, 1),
            'avg_evaluation'   => round(\App\Models\Evaluation::where('student_id', $studentId)->avg('overall_score') ?? 0, 2),
        ];

        // Approved makeup sessions
        $approvedMakeups = \App\Models\ClassSchedule::where('reschedule_student_id', $studentId)
            ->where('is_temporary', true)
            ->with(['classModule.subject', 'tutor'])
            ->orderBy('start_time', 'asc')
            ->get();

        // Rejected reschedule requests
        $rejectedRequests = \App\Models\RescheduleRequest::where('student_id', $studentId)
            ->where('status', 'rejected')
            ->with(['classModule.subject', 'tutor'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Today's classes (student's enrolled schedules happening today)
        $todayName = now()->format('l');
        $todayDate = now()->toDateString();

        $todaySchedule = \App\Models\Enrollment::where('student_id', $studentId)
            ->where('status', 'approved')
            ->whereHas('schedule', fn($q) => $q->where('day', $todayName)->where('is_temporary', 0))
            ->with(['schedule', 'classModule.subject'])
            ->get()
            ->map(function ($enroll) use ($todayDate) {
                $enroll->todayMaterial = \App\Models\LearningMaterial::where('class_id', $enroll->class_id)
                    ->where('schedule_id', $enroll->schedule_id)
                    ->whereDate('class_date', $todayDate)
                    ->first();
                return $enroll;
            });

        // Recent quiz results
        $recentQuizzes = \App\Models\QuizAttempt::where('student_id', $studentId)
            ->with('quiz')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboardStudent', compact(
            'stats', 'approvedMakeups', 'rejectedRequests', 'todaySchedule', 'recentQuizzes'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
