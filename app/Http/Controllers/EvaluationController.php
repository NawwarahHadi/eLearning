<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\CreateClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluationController extends Controller
{
    public function form(int $class_id, int $student_id)
    {
        $class   = CreateClass::with('subject')->findOrFail($class_id);
        $student = User::with('studentProfile')->findOrFail($student_id);

        $evaluation = Evaluation::where('class_id', $class_id)
            ->where('student_id', $student_id)->first();

        // // Quiz average (adjust to your quiz schema) — placeholder:
        // $quizAverage = null; // e.g. QuizResult::where('user_id',$student_id)->avg('score');

        return view('evaluation.form', compact('class', 'student', 'evaluation'));
    }

    public function store(Request $request, int $class_id, int $student_id)
    {
        $request->validate([
            'progress_level'      => ['required', 'string'],
            'understanding_score' => ['required', 'integer', 'min:1', 'max:5'],
            'participation_score' => ['required', 'integer', 'min:1', 'max:5'],
            'homework_score'      => ['required', 'integer', 'min:1', 'max:5'],
            'comments'            => ['nullable', 'string'],
        ]);

        $overall = round(
            ($request->understanding_score + $request->participation_score + $request->homework_score) / 3, 2
        );

        Evaluation::updateOrCreate(
            ['class_id' => $class_id, 'student_id' => $student_id],
            [
                'tutor_id'            => Auth::id(),
                'progress_level'      => $request->progress_level,
                'understanding_score' => $request->understanding_score,
                'participation_score' => $request->participation_score,
                'homework_score'      => $request->homework_score,
                'overall_score'       => $overall,
                'comments'            => $request->comments,
            ]
        );

        Alert::success('Success', 'Evaluation saved successfully!');
        return redirect()->route('class.show', $class_id);
    }
    public function studentEvaluations()
    {
        $evaluations = Evaluation::with(['tutor', 'createClass.subject'])
            ->where('student_id', Auth::id())
            ->latest()
            ->get();

        return view('evaluation.student-evaluations', compact('evaluations'));
    }
}
