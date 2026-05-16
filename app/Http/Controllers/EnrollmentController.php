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
    public function index()
    {
        // 1. Get the current student and their profile description
        $student = User::with('studentProfile', 'results')->find(Auth::id());

        // 2. Load classes with tutors and their profile descriptions
        $allClasses = CreateClass::with(['subject', 'tutor.tutorProfile', 'schedules', 'category'])->get();

        // 3. Fetch tutors who have completed their behavioral profile
        $tutors = User::where('role', 'tutor')
                      ->whereHas('tutorProfile', function($query) {
                          $query->whereNotNull('tutor_style_description');
                      })
                      ->with('tutorProfile')
                      ->get();

        // 4. Identify subjects where the student needs the most help[cite: 1, 2]
        $targetSubjectIds = $student->results->sortBy('score')->pluck('subject_id')->unique()->take(2)->toArray();

        // 5. If they have fewer than 2 low scores, fill with discovery subjects[cite: 1, 2]
        if (count($targetSubjectIds) < 2) {
            $discoveryIds = $allClasses->whereNotIn('subject_id', $targetSubjectIds)
                                        ->pluck('subject_id')->unique()->shuffle()
                                        ->take(2 - count($targetSubjectIds))->toArray();
            $targetSubjectIds = array_merge($targetSubjectIds, $discoveryIds);
        }

        // 6. Call Python Flask API for Text Similarity Scores[cite: 1, 2]
        $textScores = [];
        try {
            $response = Http::timeout(3)->post('http://127.0.0.1:5001/get-text-score', [
                'student_style_description' => $student->studentProfile->student_style_description ?? '',
                'tutor_style_description'   => $tutors->map(function($t) {
                    return $t->tutorProfile->tutor_style_description;
                })->toArray()
            ]);
            $textScores = $response->successful() ? $response->json()['scores'] : [];
        } catch (\Exception $e) {
            // Optional: Log error if API is down
        }

        // 7. Calculate KNN Matches[cite: 1, 2]
        $recommended = collect();
        foreach ($targetSubjectIds as $subId) {
            $classesForSubject = $allClasses->where('subject_id', $subId);

            if ($classesForSubject->isNotEmpty()) {
                $bestClass = $classesForSubject->map(function ($class) use ($student, $tutors, $textScores) {
                    // Match tutor to the index in the AI scores array[cite: 1, 2]
                    $tutorIndex = $tutors->pluck('id')->search($class->tutor_id);
                    $styleMatch = ($tutorIndex !== false && isset($textScores[$tutorIndex])) ? $textScores[$tutorIndex] * 100 : 0;

                    // Academic Need = 100 - Student's current score[cite: 1, 2]
                    $res = $student->results->where('subject_id', $class->subject_id)->first();
                    $academicNeed = $res ? (100 - $res->score) : 50;

                    // KNN Formula: Euclidean Distance in 2D space (Academic vs Style)[cite: 1, 2]
                    $distance = sqrt(pow(100 - $academicNeed, 2) + pow(100 - $styleMatch, 2));

                    $class->knn_distance = $distance;
                    $class->ai_match_percentage = round(max(0, 100 - ($distance / 1.414)), 2);

                    return $class;
                })->sortBy('knn_distance')->first();

                if ($bestClass) $recommended->push($bestClass);
            }
        }

        return view('enrollment.index', compact('recommended', 'allClasses'));
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
        $class = CreateClass::with(['schedules', 'subject'])->findOrFail($class_id);
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
        // Check users table, not tutor_profiles
        'tutor_id'      => 'required|exists:class,id',
        'schedule_id'   => 'required|array',
        'schedule_id.*' => 'exists:class_schedules,id',
    ]);

    foreach ($request->schedule_id as $scheduleId) {
        Enrollment::create([
            'student_id'  => Auth::id(), // CRITICAL: You missed this!
            'class_id'    => $request->class_id,
            'tutor_id'    => $request->tutor_id,
            'schedule_id' => $scheduleId,
            'status'      => 'approved', // Auto-approve as we discussed
        ]);
    }

    return redirect()->route('enrollment.index')->with('success', 'Enrollment created successfully!');
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
