<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\CreateClass;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;

class QuizReviewController extends Controller
{
    // PAGE 1: list every quiz (topic) in a class
    public function classQuizzes(int $classId)
    {
        $class = CreateClass::with(['subject'])->findOrFail($classId);

        $quizzes = Quiz::where('class_id', $classId)
            ->with('learningMaterial')
            ->withCount('attempts')
            ->get()
            ->map(function ($quiz) {
                $quiz->avg_score = $quiz->attempts()->avg('score');
                return $quiz;
            });

        return view('quiz-review.quizzes', compact('class', 'quizzes'));
    }

    // PAGE 2: students who attempted one quiz, with their scores
    public function attempts(int $quizId)
    {
        $quiz = Quiz::with(['learningMaterial', 'createClass.subject'])->findOrFail($quizId);

        $attempts = QuizAttempt::where('quiz_id', $quizId)
            ->with('student')
            ->latest('updated_at')
            ->get();

        return view('quiz-review.attempts', compact('quiz', 'attempts'));
    }

    // PAGE 3: one student's answers for one quiz
    public function studentAnswers(int $quizId, int $studentId)
    {
        $quiz     = Quiz::with('learningMaterial')->findOrFail($quizId);
        $student  = User::findOrFail($studentId);
        $attempt  = QuizAttempt::where('quiz_id', $quizId)
                        ->where('student_id', $studentId)
                        ->firstOrFail();

        $questions = QuizQuestion::where('quiz_id', $quizId)->get();

        // selected_answers is JSON keyed by question id: {"1":"A","2":"B"}
        $selected = $attempt->selected_answers ?? [];

        return view('quiz-review.answers', compact('quiz', 'student', 'attempt', 'questions', 'selected'));
    }
}
