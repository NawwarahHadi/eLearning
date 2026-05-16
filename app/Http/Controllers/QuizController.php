<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // --- TUTOR SIDE ---
    public function create(int $learning_material_id)
    {
        // Find the material to get the class_id automatically
        $material = LearningMaterial::findOrFail($learning_material_id);

        // Pass both IDs to the view
        return view('Quiz.tutor-create', [
            'material' => $material,
            'class_id' => $material->class_id
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'learning_material_id' => 'required|exists:learning_materials,id', // Ensure material exists
            'questions.*.text' => 'required',
            'questions.*.correct' => 'required',
        ]);

        // 1. Create the Quiz and link it to the Material
        $quiz = Quiz::create([
            'class_id' => $request->class_id,
            'tutor_id' => Auth::id(),
            'learning_material_id' => $request->learning_material_id, // New Column
            'title' => $request->title,
        ]);

        // 2. Save Questions
        foreach ($request->questions as $q) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $q['text'],
                'option_a' => $q['a'],
                'option_b' => $q['b'],
                'option_c' => $q['c'],
                'option_d' => $q['d'],
                'correct_option' => $q['correct'],
            ]);
        }

        return redirect()->back()->with('success', 'Quiz successfully linked to your notes!');
    }

    public function play(int $quiz_id) {
        $quiz = Quiz::with(['questions', 'learningMaterial'])->findOrFail($quiz_id);
        return view('Quiz.student-answer-quiz', compact('quiz'));
    }

    public function submit(Request $request, int $quiz_id)
    {
        $quiz = Quiz::with('questions')->findOrFail($quiz_id);
        $correct = 0;
        $results = []; // To store which questions were right/wrong

        foreach ($quiz->questions as $q) {
            $userAnswer = $request->input('q_'.$q->id);
            $isCorrect = ($userAnswer === $q->correct_option);

            if ($isCorrect) {
                $correct++;
            }

            // Store details for the same-page display
            $results[$q->id] = [
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'correct_option' => $q->correct_option
            ];
        }

        $total = $quiz->questions->count();
        $score = round(($correct / $total) * 100);


        QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => Auth::id(),
            'score' => $score,
            'total_questions' => $total,
            'correct_answers' => $correct,
        ]);

        // STAY ON PAGE: Return the view with the score and results
        return view('Quiz.student-answer-quiz', compact('quiz', 'score', 'correct', 'total', 'results'));
    }
}
