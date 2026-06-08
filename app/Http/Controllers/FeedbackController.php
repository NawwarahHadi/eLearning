<?php

namespace App\Http\Controllers;

use App\Models\CreateClass;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $class_id)
    {
        $class = CreateClass::findOrFail($class_id);
        $tutor = $class->tutor; // Assuming a relationship exists

        return view('feedback.index', compact('class', 'tutor'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'tutor_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:class,id', // <-- Ensure class table name matches yours
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // 2. Create the feedback with the class_id included
        Feedback::create([
            'student_id' => Auth::id(),
            'tutor_id' => $request->tutor_id,
            'class_id' => $request->class_id, // <-- Add this line
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Feedback submitted successfully.');
}


    public function showAdmin()
    {
        // Fetches everything for Admin with eager loading to prevent N+1 issues
        $feedbacks = Feedback::with(['student', 'class.tutor'])->latest()->get();

        return view('feedback.admin', compact('feedbacks'));
    }

    public function showTutor()
    {
        $feedbacks = Feedback::where('tutor_id', Auth::id())
                    ->with(['student', 'class'])
                    ->latest()
                    ->get();

        return view('feedback.tutor-report', compact('feedbacks'));
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
