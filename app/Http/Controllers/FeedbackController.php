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
        $request->validate([
            'tutor_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Feedback::create([
            // 'student_id' =>Auth::classid(),
            'student_id' => $request->student_id,
            'tutor_id' => $request->tutor_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Feedback submitted successfully.');
    }


    public function showAdmin()
    {
        // Fetches everything for Admin
        $feedbacks = Feedback::with(['student', 'class'])->latest()->get();
        return view('feedback.index', compact('feedbacks'));
    }

    public function showTutor()
    {
        // Fetches only for the logged-in tutor
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
