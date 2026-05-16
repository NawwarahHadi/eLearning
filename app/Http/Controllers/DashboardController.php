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
        // Static stats for the Student's personal dashboard
        $stats = [
            'enrolled_classes' => 4,
            'attendance_percentage' => 95,
            'assignments_done' => 12,
            'current_gpa' => 3.85
        ];

        return view('dashboardStudent', compact('stats'));
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
