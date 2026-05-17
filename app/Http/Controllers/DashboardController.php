<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // Static stats for the Tutor's personal performance
        $stats = [
            'total_classes' => 5,
            'total_students' => 120,
            'avg_rating' => 4.8,
            'attendance_rate' => 92
        ];

        return view('dashboardTutor', compact('stats'));
    }

    public function indexSTudent()
    {
        $studentId = \Illuminate\Support\Facades\Auth::id();

        $stats = [
            'enrolled_classes' => 4,
            'attendance_percentage' => 95,
            'assignments_done' => 12,
            'current_gpa' => 3.85
        ];

        // 🟢 Fetch Approved Makeup Sessions from the Live Calendar Engine
        $approvedMakeups = \App\Models\ClassSchedule::where('reschedule_student_id', $studentId)
            ->where('is_temporary', true)
            ->with(['classModule.subject', 'tutor'])
            ->orderBy('start_time', 'asc')
            ->get();

        // 🔴 NEW: Fetch Rejected Requests directly from the Log Table history
        $rejectedRequests = \App\Models\RescheduleRequest::where('student_id', $studentId)
            ->where('status', 'rejected')
            ->with(['classModule.subject', 'tutor'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Pass everything to the view layout
        return view('dashboardStudent', compact('stats', 'approvedMakeups', 'rejectedRequests'));
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
