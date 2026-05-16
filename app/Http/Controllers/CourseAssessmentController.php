<?php

namespace App\Http\Controllers;

use App\Models\CourseAssessment;
use App\Models\CreateClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class CourseAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $class_id )
    {
        $class = CreateClass::findOrFail($class_id);
        $tutor = $class->tutor; // Assuming a relationship exists

        return view('assessments.index', compact('class', 'tutor'));
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
    public function store(Request $request) {
        // Validate that all scores are between 1 and 5
        $validated = $request->validate([
            'plo1_score' => 'required|integer|min:1|max:5',
            'plo2_score'=> 'required|integer|min:1|max:5',
            'content_relevance'=> 'required|integer|min:1|max:5',
            'content_updated'=> 'required|integer|min:1|max:5',
            'delivery_elearn'=> 'required|integer|min:1|max:5',
            'delivery_facilities'=> 'required|integer|min:1|max:5',
            'assess_continuous'=> 'required|integer|min:1|max:5',
            'assess_load'=> 'required|integer|min:1|max:5',
            'overall_comments' => 'nullable|string',

        ]);

        CourseAssessment::create(array_merge($validated, [
            'student_id' => Auth::id(),
            'tutor_id' => $request->tutor_id,
            'class_id' => $request->class_id,

        ]));

       Alert::success('Success', 'Thank you for your assessment!');

        return redirect()->route('student.class.index');

    }

    /**
     * Display the specified resource.
     */
    public function showAdmin()
    {
        // Admin sees average scores for ALL classes to identify top/bottom performers
        $assessments = CourseAssessment::select('class_id',
            DB::raw('AVG(plo1_score) as avg_plo1'),
            DB::raw('AVG(plo2_score) as avg_plo2'),
            DB::raw('COUNT(id) as total_responses')
        )
        ->groupBy('class_id')
        ->with('class') // Ensure relationship is defined in Model
        ->get();

        return view('assessments.admin_report', compact('assessments'));
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
