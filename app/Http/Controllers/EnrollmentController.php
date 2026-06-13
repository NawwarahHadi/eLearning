<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CreateClass;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Display recommendations and all available classes.
     */
    // public function index()
    // {
    //     // 1. Get the current student and their profile description
    //     $student = User::with('studentProfile', 'results')->find(Auth::id());

    //     // 2. Load classes with tutors and their profile descriptions
    //     $allClasses = CreateClass::with(['subject', 'tutor.tutorProfile', 'schedules', 'category'])->get();

    //     // 3. Fetch tutors who have completed their behavioral profile
    //     $tutors = User::where('role', 'tutor')
    //                   ->whereHas('tutorProfile', function($query) {
    //                       $query->whereNotNull('tutor_style_description');
    //                   })
    //                   ->with('tutorProfile')
    //                   ->get();

    //     // 4. Identify subjects where the student needs the most help[cite: 1, 2]
    //     $targetSubjectIds = $student->results->sortBy('score')->pluck('subject_id')->unique()->take(2)->toArray();

    //     // 5. If they have fewer than 2 low scores, fill with discovery subjects[cite: 1, 2]
    //     if (count($targetSubjectIds) < 2) {
    //         $discoveryIds = $allClasses->whereNotIn('subject_id', $targetSubjectIds)
    //                                     ->pluck('subject_id')->unique()->shuffle()
    //                                     ->take(2 - count($targetSubjectIds))->toArray();
    //         $targetSubjectIds = array_merge($targetSubjectIds, $discoveryIds);
    //     }

    //     // 6. Call Python Flask API for Text Similarity Scores[cite: 1, 2]
    //     $textScores = [];
    //     try {
    //         $response = Http::timeout(3)->post('http://127.0.0.1:5000/get-text-score', [
    //             'student_style_description' => $student->studentProfile->student_style_description ?? '',
    //             'tutor_style_description'   => $tutors->map(function($t) {
    //                 return $t->tutorProfile->tutor_style_description;
    //             })->toArray()
    //         ]);
    //         $textScores = $response->successful() ? $response->json()['scores'] : [];
    //     } catch (\Exception $e) {
    //         // Optional: Log error if API is down
    //     }

    //     // 7. Calculate KNN Matches[cite: 1, 2]
    //     $recommended = collect();
    //     foreach ($targetSubjectIds as $subId) {
    //         $classesForSubject = $allClasses->where('subject_id', $subId);

    //         if ($classesForSubject->isNotEmpty()) {
    //             $bestClass = $classesForSubject->map(function ($class) use ($student, $tutors, $textScores) {
    //                 // Match tutor to the index in the AI scores array[cite: 1, 2]
    //                 $tutorIndex = $tutors->pluck('id')->search($class->tutor_id);
    //                 $styleMatch = ($tutorIndex !== false && isset($textScores[$tutorIndex])) ? $textScores[$tutorIndex] * 100 : 0;

    //                 // Academic Need = 100 - Student's current score[cite: 1, 2]
    //                 $res = $student->results->where('subject_id', $class->subject_id)->first();
    //                 $academicNeed = $res ? (100 - $res->score) : 50;

    //                 // KNN Formula: Euclidean Distance in 2D space (Academic vs Style)[cite: 1, 2]
    //                 $distance = sqrt(pow(100 - $academicNeed, 2) + pow(100 - $styleMatch, 2));

    //                 $class->knn_distance = $distance;
    //                 $class->ai_match_percentage = round(max(0, 100 - ($distance / 1.414)), 2);

    //                 return $class;
    //             })->sortBy('knn_distance')->first();

    //             if ($bestClass) $recommended->push($bestClass);
    //         }
    //     }

    //     return view('enrollment.index', compact('recommended', 'allClasses'));
    // }

//     public function index()
// {
//     $student = User::with('studentProfile', 'results')->find(Auth::id());

//     $allClasses = CreateClass::with(['subject', 'tutor.tutorProfile', 'schedules', 'category'])->get();

//     $tutors = User::where('role', 'tutor')
//                   ->whereHas('tutorProfile', function ($query) {
//                       $query->whereNotNull('tutor_style_description');
//                   })
//                   ->with('tutorProfile')
//                   ->get();

//     // ───────────── DEBUG 1: basic data check ─────────────
//     dump([
//         'logged_in_user_id'   => Auth::id(),
//         'student_found'       => $student ? 'YES' : 'NO',
//         'student_results_cnt' => $student->results->count(),
//         'student_results'     => $student->results->pluck('score', 'subject_id')->toArray(),
//         'all_classes_cnt'     => $allClasses->count(),
//         'classes_subject_ids' => $allClasses->pluck('subject_id')->toArray(),
//         'classes_levels'      => $allClasses->pluck('level', 'subject_id')->toArray(),
//         'classes_categories'  => $allClasses->pluck('category_code')->unique()->values()->toArray(),
//         'tutors_with_style'   => $tutors->count(),
//     ]);
//     // ──────────────────────────────────────────────────────

//     // Flask text-similarity scores
//     $textScores = [];
//     try {
//         $response = Http::timeout(3)->post('http://127.0.0.1:5000/get-text-score', [
//             'student_style_description' => $student->studentProfile->student_style_description ?? '',
//             'tutor_style_description'   => $tutors->map(fn ($t) => $t->tutorProfile->tutor_style_description)->toArray(),
//         ]);
//         $textScores = $response->successful() ? $response->json()['scores'] : [];
//     } catch (\Exception $e) {
//         dump('FLASK API ERROR: ' . $e->getMessage()); // DEBUG 2
//     }

//     $recommended = collect();

//     foreach ($student->results as $result) {
//         $subId          = $result->subject_id;
//         $studentScore   = $result->score;
//         $recommendLevel = $this->scoreToLevel($studentScore);

//         // ───────────── DEBUG 3: per-subject loop ─────────────
//         $exactMatch = $allClasses->where('subject_id', $subId)->where('level', $recommendLevel);
//         $anyForSubject = $allClasses->where('subject_id', $subId);

//         dump([
//             'subject_id'              => $subId,
//             'student_score'           => $studentScore,
//             'recommend_level'         => $recommendLevel,
//             'classes_for_subject_cnt' => $anyForSubject->count(),
//             'exact_level_match_cnt'   => $exactMatch->count(),
//         ]);
//         // ──────────────────────────────────────────────────────

//         $matchedClasses = $exactMatch;

//         if ($matchedClasses->isEmpty()) {
//             $matchedClasses = $anyForSubject
//                 ->sortBy(fn ($c) => abs($this->levelRank($c->level) - $this->levelRank($recommendLevel)));
//         }

//         if ($matchedClasses->isEmpty()) {
//             dump("→ SKIPPED subject {$subId}: no class for this subject at all");
//             continue;
//         }

//         $bestClass = $matchedClasses->map(function ($class) use ($tutors, $textScores, $studentScore, $recommendLevel) {
//             $tutorIndex = $tutors->pluck('id')->search($class->tutor_id);
//             $styleMatch = ($tutorIndex !== false && isset($textScores[$tutorIndex]))
//                 ? $textScores[$tutorIndex] * 100
//                 : 0;

//             $class->ai_match_percentage = round($styleMatch, 2);
//             $class->student_score       = $studentScore;
//             $class->recommend_level     = $recommendLevel;
//             $class->level_matched       = ($class->level === $recommendLevel);

//             return $class;
//         })->sortByDesc('ai_match_percentage')->first();

//         if ($bestClass) $recommended->push($bestClass);
//     }

//     // ───────────── DEBUG 4: final result ─────────────
//     dd([
//         'FINAL_recommended_count' => $recommended->count(),
//         'recommended_subjects'    => $recommended->pluck('subject_id')->toArray(),
//     ]);
//     // ──────────────────────────────────────────────────

//     return view('enrollment.index', compact('recommended', 'allClasses'));
// }

    public function index()
    {

        $student = User::with('studentProfile', 'results')->find(Auth::id());

        $allClasses = CreateClass::with(['subject', 'tutor.tutorProfile', 'schedules', 'category'])->get();

        $tutors = User::where('role', 'tutor')
                    ->whereHas('tutorProfile', function ($query) {
                        $query->whereNotNull('tutor_style_description');
                    })
                    ->with('tutorProfile')
                    ->get();

        // Flask text-similarity scores (tutor teaching style vs student learning style)
        $textScores = [];
        try {
            $response = Http::timeout(3)->post('http://127.0.0.1:5000/get-text-score', [
                'student_style_description' => $student->studentProfile->student_style_description ?? '',
                'tutor_style_description'   => $tutors->map(fn ($t) => $t->tutorProfile->tutor_style_description)->toArray(),
            ]);
            $textScores = $response->successful() ? $response->json()['scores'] : [];
        } catch (\Exception $e) {
            // log if API down
        }

        $recommended = collect();

        // Go through every subject the student has a result for
        foreach ($student->results as $result) {
            $subId         = $result->subject_id;
            $studentScore  = $result->score;
            $recommendLevel = $this->scoreToLevel($studentScore); // low / medium / good

            // Only classes for this subject AT the recommended level
            $matchedClasses = $allClasses
                ->where('subject_id', $subId)
                ->where('level', $recommendLevel);

            // Fallback: if no class exists at the exact level, take the closest level
            if ($matchedClasses->isEmpty()) {
                $matchedClasses = $allClasses->where('subject_id', $subId)
                    ->sortBy(fn ($c) => abs($this->levelRank($c->level) - $this->levelRank($recommendLevel)));
            }

            if ($matchedClasses->isEmpty()) continue;

            // Among matched classes, pick the best tutor by style match
            $bestClass = $matchedClasses->map(function ($class) use ($tutors, $textScores, $studentScore, $recommendLevel) {
                $tutorIndex = $tutors->pluck('id')->search($class->tutor_id);
                $styleMatch = ($tutorIndex !== false && isset($textScores[$tutorIndex]))
                    ? $textScores[$tutorIndex] * 100
                    : 0;

                $class->ai_match_percentage = round($styleMatch, 2);
                $class->student_score       = $studentScore;
                $class->recommend_level     = $recommendLevel;
                $class->level_matched       = ($class->level === $recommendLevel);

                return $class;
            })->sortByDesc('ai_match_percentage')->first();

            if ($bestClass) $recommended->push($bestClass);
        }

        return view('enrollment.index', compact('recommended', 'allClasses'));
    }


    private function scoreToLevel(float $score): string
    {
        if ($score < 40)  return 'low';
        if ($score <= 70) return 'medium';
        return 'good';
    }


    private function levelRank(?string $level): int
    {
        return ['low' => 0, 'medium' => 1, 'good' => 2][$level] ?? 1;
    }

    /**
     * Show available tutors for a specific subject/category.
     */
    public function tutorSelection(int $class_id, $recommended_tutor_id = null)
    {
        $baseClass = CreateClass::findOrFail($class_id);

        // Now we eager load ALL enrollments because everyone is auto-approved
        $classes = CreateClass::with(['tutor.tutorProfile', 'enrollments'])
            ->where('subject_id', $baseClass->subject_id)
            ->where('category_code', $baseClass->category_code)
            ->get();

        return view('enrollment.tutors', [
            'class' => $baseClass,
            'classes' => $classes,
            'recommended_tutor_id' => $recommended_tutor_id
        ]);
    }

    /**
     * View a specific tutor's profile and class details.
     */
    public function tutorProfile(int $class_id, int $tutor_id)
    {
        $class = CreateClass::with([
            'schedules' => function($query) {
                $query->where('is_temporary', 0);
            },
            'subject'
        ])->findOrFail($class_id);

        $tutor = User::with('tutorProfile')->findOrFail($tutor_id);

        return view('enrollment.tutor_profile', compact('class', 'tutor'));
    }
    /**
     * Submit a new enrollment request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id'      => 'required|exists:class,id',
            'tutor_id'      => 'required|exists:users,id',
            'schedule_id'   => 'required|array|min:1',
            'schedule_id.*' => 'exists:class_schedules,id', // make sure table name matches
        ]);

        foreach ($request->schedule_id as $scheduleId) {
            Enrollment::create([
                'class_id'    => $request->class_id,
                'tutor_id'    => $request->tutor_id,
                'schedule_id' => $scheduleId,
                'student_id'  => Auth::id(),
                'status'      => 'approve',
            ]);
        }

        return redirect()->route('enrollment.index') ->with('success', 'Enrollment request submitted successfully.');

    }

    /**
     * Handle tutor change requests (Canceled old record, approves new).
     */
    public function approveChange(Request $request, int $newEnrollmentId)
    {
        $newEnrollment = Enrollment::findOrFail($newEnrollmentId);

        // Cancel the old approved record for the same subject[cite: 1, 2]
        $oldEnrollment = Enrollment::where('student_id', $newEnrollment->student_id)
            ->where('class_id', $newEnrollment->class_id)
            ->where('status', 'approved')
            ->where('id', '!=', $newEnrollment->id)
            ->first();

        if ($oldEnrollment) {
            $oldEnrollment->update(['status' => 'rejected']);
        }

        $newEnrollment->update(['status' => 'approved']);

        return back()->with('success', 'Tutor swap completed successfully.');
    }
}
