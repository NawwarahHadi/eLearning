<?php

namespace App\Http\Controllers;

use App\Models\CreateClass;
use App\Models\LearningMaterial;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\User; // <--- Added this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{

    public function create(int $learning_material_id)
    {
        $material = LearningMaterial::findOrFail($learning_material_id);

        return view('Quiz.tutor-create', [
            'material' => $material,
            'class_id' => $material->class_id
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'learning_material_id' => 'required|exists:learning_materials,id',
            'questions.*.text' => 'required',
            'questions.*.correct' => 'required',
        ]);

        $quiz = Quiz::create([
            'class_id' => $request->class_id,
            'tutor_id' => Auth::id(),
            'learning_material_id' => $request->learning_material_id,
            'title' => $request->title,
        ]);

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


    public function play(int $quiz_id)
    {
        $quiz = Quiz::with(['questions', 'learningMaterial'])->findOrFail($quiz_id);
        return view('Quiz.student-answer-quiz', compact('quiz'));
    }

    public function submit(Request $request, int $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $totalQuestions = $quiz->questions->count();
        $correctCount = 0;
        $results = [];
        $savedAnswers = []; // Array tracking question_id => selected_option ('a', 'b', etc.)

        // 2. Loop through questions to validate answers and calculate metrics
        foreach ($quiz->questions as $question) {
            $userAnswer = $request->input('q_' . $question->id); // Captures student input selection
            $isCorrect = ($userAnswer === $question->correct_option);

            if ($isCorrect) {
                $correctCount++;
            }

            // Save the chosen key string mapped to the unique question ID
            $savedAnswers[$question->id] = $userAnswer;

            // Payload structure for the instant result page display cards
            $results[$question->id] = [
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'correct_option' => $question->correct_option
            ];
        }

       // 1. Calculate score percentage (Keep this where it is)
        $scorePercentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $studentId = Auth::id();

        // === CRITICAL FIX: Add this line right here to define the variable! ===
        $jsonAnswersString = json_encode($savedAnswers);

    // 2. The Absolute Upsert Engine
    $attempt = QuizAttempt::updateOrCreate(
        [
            'quiz_id' => $quiz->id,
            'student_id' => $studentId,
        ],
        [
            'score' => $scorePercentage,
            'selected_answers' => $jsonAnswersString, // This error message will now instantly disappear!
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
        ]
    );

        $attempt = QuizAttempt::updateOrCreate(
            [
                'quiz_id' => $quiz->id,
                'student_id' => $studentId,
            ],
            [
                'score' => $scorePercentage,
                'selected_answers' => $jsonAnswersString,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctCount,
            ]
        );


        $user = Auth::user();
        $leveledUp = false;
        $xpEarned = 0;

        if ($user instanceof User) {
            // Base reward computation: 100 XP per accurate response point
            $xpEarned = ($correctCount * 100);

            // Mastery unlock milestone bonus multiplier (score >= 80%)
            if ($scorePercentage >= 80) {
                $xpEarned += 200;
            }

            // Apply earned rewards to user stats container profiles
            $user->xp += $xpEarned;
            $oldLevel = $user->level;

            // Progression tier math calculation step mapping function
            $user->level = floor($user->xp / 1000) + 1;
            $user->save();

            $leveledUp = ($user->level > $oldLevel);
        }

        // 5. Render output view card layout with loaded payload variables
        return view('Quiz.student-answer-quiz', [
            'quiz' => $quiz,
            'score' => $scorePercentage,
            'correct' => $correctCount,
            'total' => $totalQuestions,
            'results' => $results,
            'xpEarned' => $xpEarned,
            'leveledUp' => $leveledUp
        ]);
    }


    public function showMap( int $class_id)
    {

        $materials = LearningMaterial::where('class_id', $class_id)
            ->with(['quiz.attempts' => function($query) {
                $query->where('student_id', auth::id());
            }])
            ->orderBy('created_at', 'asc') // This sets the path order
            ->get();

        // Find the class details for the map title
        $class = CreateClass::findOrFail($class_id);

        return view('Quiz.student-map', compact('materials', 'class'));
    }

    public function review(int $quiz_id)
    {
        $quiz = Quiz::with('questions')->findOrFail($quiz_id);

        $latestAttempt = QuizAttempt::where('quiz_id', $quiz_id)
            ->where('student_id', auth::id())
            ->latest()
            ->first();

        if (!$latestAttempt) {
            return redirect()->route('quiz.play', $quiz_id);
        }

        // CRITICAL ENGINE SAFEGUARD: Decode JSON safely if it's stored as a raw text string
        $selectedAnswers = $latestAttempt->selected_answers;
        if (is_string($selectedAnswers)) {
            $selectedAnswers = json_decode($selectedAnswers, true);
        }

        return view('Quiz.student-review-quiz', compact('quiz', 'latestAttempt', 'selectedAnswers'));
    }
}
