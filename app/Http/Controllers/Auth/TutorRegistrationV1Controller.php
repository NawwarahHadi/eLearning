<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\Subject;
use App\Models\User;
use App\Models\TutorProfile;
use App\Models\TutorProfile as ModelsTutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class TutorRegistrationController extends Controller
{
    public function create()
    {
        $listSubject = Subject::all();
        $level       = EducationLevel::all();

        $data = [
            'listSubject' => $listSubject,
            'level' => $level,
        ];

        return view('auth.register-tutor', $data);
    }

    // public function store(Request $request)
    // {
    // //    dd($request->all());
    //     $request->validate([
    //         'name'                => ['required', 'string', 'max:255'],
    //         'email'               => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password'            => Hash::make(\Illuminate\Support\Str::random(16)),
    //         'age'                 => ['required', 'numeric'],
    //         'address'             => ['required', 'string'],
    //         'experience'          => ['required', 'numeric'],
    //         'education_level_id'  => ['required','exists:education_level,id'],
    //         'cgpa'                => ['required', 'numeric'],
    //         'subject_expertise'   => ['required', 'array'],
    //         'subject_expertise.*' => ['exists:subjects,id'],
    //         'tutor_style_description'         => ['required', 'string', 'min:20'],
    //         'profile_photo'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
    //         'tutor_cert' => ['required', 'string'],
    //         'resume'     => ['required', 'string'],
    //     ]);

    //     // 1. Create User (Status is Pending)
    //     $user = User::create([
    //         'name'       => $request->name,
    //         'email'      => $request->email,
    //         'password'   => Hash::make($request->password),
    //         'role'       => 'tutor',
    //         'status'     => 'pending',
    //     ]);

    //     $user->addRole('tutor');

    //     $photoPath = null;
    //     if ($request->hasFile('profile_photo')) {
    //         // This saves it to storage/app/public/photos
    //         $photoPath = $request->file('profile_photo')->store('photos', 'public');
    //     }
    //     // $certPath  = $request->file('tutor_cert')->store('certificates', 'public');
    //     // $resumePath = $request->file('resume')->store('resumes', 'public');

    //    $fileFields =
    //    [
    //     'resume'=>'resumes',
    //     'tutor_cert' => 'certificates'
    //    ];

    //    $finalPaths = [];
    //     foreach ($fileFields as $field => $folder) {
    //         $tmpPath = $request->input($field);
    //         if ($tmpPath) {
    //             $filename = basename($tmpPath);
    //             $newPath = "$folder/$filename";

    //             if (Storage::disk('public')->exists($tmpPath)) {
    //                 Storage::disk('public')->move($tmpPath, $newPath);
    //                 $finalPaths[$field] = $newPath;
    //             }
    //         }
    //     }

    //     $finalScore = $this->calculateQualificationScore($request, $finalPaths);
    //     $ranking = 'Standard';
    //     if ($finalScore >= 80) $ranking = 'Highly Recommended';
    //     elseif ($finalScore >= 60) $ranking = 'Recommended';
    //     if ($request->has('subject_expertise')) {
    //     $user->subjects()->attach($request->subject_expertise);
    //     }

    //     // 4. Create Tutor Profile
    //     TutorProfile::create([
    //         'user_id'                 => $user->id,
    //         'profile_photo'           => $photoPath,
    //         'address'                 => $request->address,
    //         'age'                     => $request->age,
    //         'experience'              => $request->experience,
    //         'education_level_id'      => $request ->education_level_id,
    //         'cgpa'                    => $request ->cgpa,
    //         'tutor_cert'              => $finalPaths['tutor_cert'] ?? null,
    //         'resume'                  => $finalPaths['resume'] ?? null,
    //         'tutor_style_description' => $request->tutor_style_description,
    //         'qualification_score'     => $finalScore,
    //         'recommendation_status'   => $ranking,

    //     ]);
    //     // -------------------------


    //     return redirect()->route('login')->with('status', 'Application submitted! Admin will review your qualification score soon.');
    // }

    // public function store(Request $request)
    // {
    // //    dd($request->all());
    //     $request->validate([
    //         'name'                => ['required', 'string', 'max:255'],
    //         'email'               => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password'            => Hash::make(\Illuminate\Support\Str::random(16)),
    //         'age'                 => ['required', 'numeric'],
    //         'address'             => ['required', 'string'],
    //         'experience'          => ['required', 'numeric'],
    //         'education_level_id'  => ['required','exists:education_level,id'],
    //         'cgpa'                => ['required', 'numeric'],
    //         'subject_expertise'   => ['required', 'array'],
    //         'subject_expertise.*' => ['exists:subjects,id'],
    //         'tutor_style_description'         => ['required', 'string', 'min:20'],
    //         'profile_photo'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
    //         'tutor_cert' => ['required', 'string'],
    //         'resume'     => ['required', 'string'],
    //     ]);

    //     // 1. Create User (Status is Pending)
    //     $user = User::create([
    //         'name'       => $request->name,
    //         'email'      => $request->email,
    //         'password'   => Hash::make($request->password),
    //         'role'       => 'tutor',
    //         'status'     => 'pending',
    //     ]);

    //     $user->addRole('tutor');

    //     $photoPath = null;
    //     if ($request->hasFile('profile_photo')) {
    //         // This saves it to storage/app/public/photos
    //         $photoPath = $request->file('profile_photo')->store('photos', 'public');
    //     }
    //     // $certPath  = $request->file('tutor_cert')->store('certificates', 'public');
    //     // $resumePath = $request->file('resume')->store('resumes', 'public');

    //    $fileFields =
    //    [
    //     'resume'=>'resumes',
    //     'tutor_cert' => 'certificates'
    //    ];

    //    $finalPaths = [];
    //     foreach ($fileFields as $field => $folder) {
    //         $tmpPath = $request->input($field);
    //         if ($tmpPath) {
    //             $filename = basename($tmpPath);
    //             $newPath = "$folder/$filename";

    //             if (Storage::disk('public')->exists($tmpPath)) {
    //                 Storage::disk('public')->move($tmpPath, $newPath);
    //                 $finalPaths[$field] = $newPath;
    //             }
    //         }
    //     }

    //     $aiData = [
    //         'university' => 'Not Extracted',
    //         'course'     => 'Not Extracted',
    //         'ai_summary' => 'No Summary Generated',
    //         'experience_titles' => [],
    //         'suggested_subjects' => []
    //     ];

    //     if (isset($finalPaths['resume'])) {
    //         $fullResumePath = storage_path("app/public/" . $finalPaths['resume']);

    //         try {
    //             // Connecting to your Python AI Engine
    //             $response = Http::timeout(15)->post('http://127.0.0.1:5001/extract', [
    //                 'path' => $fullResumePath
    //             ]);

    //             if ($response->successful()) {
    //                 $extracted = $response->json();
    //                 $aiData['university']         = $extracted['university'];
    //                 $aiData['course']             = $extracted['course'];
    //                 $aiData['ai_summary']         = $extracted['summary'];
    //                 $aiData['experience_titles']   = $extracted['experience_titles'];
    //                 $aiData['suggested_subjects']  = $extracted['suggested_subjects'];
    //             }
    //         } catch (\Exception $e) {
    //             // Log error if AI server is down
    //             Log::error("AI Server Error: " . $e->getMessage());
    //         }
    //     }

    //     $finalScore = $this->calculateQualificationScore($request, $finalPaths);
    //     $ranking = 'Standard';
    //     if ($finalScore >= 80) $ranking = 'Highly Recommended';
    //     elseif ($finalScore >= 60) $ranking = 'Recommended';
    //     if ($request->has('subject_expertise')) {
    //     $user->subjects()->attach($request->subject_expertise);
    //     }

    //     // 4. Create Tutor Profile
    //     TutorProfile::create([
    //         'user_id'                 => $user->id,
    //         'profile_photo'           => $photoPath,
    //         'address'                 => $request->address,
    //         'age'                     => $request->age,
    //         'experience'              => $request->experience,
    //         'education_level_id'      => $request ->education_level_id,
    //         'cgpa'                    => $request ->cgpa,
    //         'tutor_cert'              => $finalPaths['tutor_cert'] ?? null,
    //         'resume'                  => $finalPaths['resume'] ?? null,
    //         'tutor_style_description' => $request->tutor_style_description,
    //         'qualification_score'     => $finalScore,
    //         'recommendation_status'   => $ranking,
    //         'university'              => $aiData['university'],
    //         'course'                  => $aiData['course'],
    //         'ai_summary'              => $aiData['ai_summary'],
    //         // 'experience_titles'       => $aiData['experience_titles'],
    //         // 'suggested_subjects'      => $aiData['suggested_subjects'],
    //         'experience_titles'       => json_encode($aiData['experience_titles']),
    //         'suggested_subjects'      => json_encode($aiData['suggested_subjects']),

    //     ]);
    //     // -------------------------


    //     return redirect()->route('login')->with('status', 'Application submitted! Admin will review your qualification score soon.');
    // }

    public function store(Request $request)
{
    $request->validate([
        'name'                    => ['required', 'string', 'max:255'],
        'email'                   => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password'                => ['nullable', 'string', 'min:8'],
        'age'                     => ['required', 'numeric'],
        'address'                 => ['required', 'string'],
        'experience'              => ['required', 'numeric'],
        'education_level_id'      => ['required', 'exists:education_level,id'],
        'cgpa'                    => ['required', 'numeric'],
        'subject_expertise'       => ['required', 'array'],
        'subject_expertise.*'     => ['exists:subjects,id'],
        'tutor_style_description' => ['required', 'string', 'min:20'],
        'profile_photo'           => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
    ]);

    // 1. Create User
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'tutor',
        'status'   => 'pending',
    ]);

    $user->addRole('tutor');

    // 2. Handle Profile Photo
    $photoPath = null;
    if ($request->hasFile('profile_photo')) {
        $photoPath = $request->file('profile_photo')->store('photos', 'public');
    }

    // 3. Robust File Management Handler (Bypasses FilePond for Postman)
    $finalPaths = [];
    $fileFields = ['resume' => 'resumes', 'tutor_cert' => 'certificates'];

    foreach ($fileFields as $field => $folder) {
        if ($request->hasFile($field)) {
            // Postman Direct Upload Track
            $finalPaths[$field] = $request->file($field)->store($folder, 'public');
        } else {
            // Browser FilePond String Input Track
            $tmpPath = $request->input($field);
            if ($tmpPath) {
                $filename = basename($tmpPath);
                $newPath = "$folder/$filename";
                if (Storage::disk('public')->exists($tmpPath)) {
                    Storage::disk('public')->move($tmpPath, $newPath);
                    $finalPaths[$field] = $newPath;
                }
            }
        }
    }

    // 4. Initialize AI Payload Placeholders
    $aiData = [
        'university'         => 'Not Extracted',
        'course'             => 'Not Extracted',
        'ai_summary'         => 'No Summary Generated',
        'experience_titles'  => [],
        'suggested_subjects' => []
    ];

    // 5. Port 5001 AI Server Bridge
    if (isset($finalPaths['resume'])) {
        $fullResumePath = storage_path("app/public/" . $finalPaths['resume']);

        try {
            $response = Http::timeout(15)->post('http://127.0.0.1:5001/extract', [
                'path' => $fullResumePath
            ]);

            if ($response->successful()) {
                $extracted = $response->json();
                $aiData['university']         = $extracted['university'] ?? 'Not Extracted';
                $aiData['course']             = $extracted['course'] ?? 'Not Extracted';
                $aiData['ai_summary']         = $extracted['summary'] ?? 'No Summary Generated';
                $aiData['experience_titles']   = $extracted['experience_titles'] ?? [];
                $aiData['suggested_subjects']  = $extracted['suggested_subjects'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error("AI Server Handshake Failed: " . $e->getMessage());
        }
    }

    // 6. Calculate Expert System Scores
    $finalScore = $this->calculateQualificationScore($request, $finalPaths);
    $ranking = 'Standard';
    if ($finalScore >= 80) $ranking = 'Highly Recommended';
    elseif ($finalScore >= 60) $ranking = 'Recommended';

    if ($request->has('subject_expertise')) {
        $user->subjects()->attach($request->subject_expertise);
    }

    // 7. Write Structured Profile Row to Database
    TutorProfile::create([
        'user_id'                 => $user->id,
        'profile_photo'           => $photoPath,
        'address'                 => $request->address,
        'age'                     => $request->age,
        'experience'              => $request->experience,
        'education_level_id'      => $request->education_level_id,
        'cgpa'                    => $request->cgpa,
        'tutor_cert'              => $finalPaths['tutor_cert'] ?? null,
        'resume'                  => $finalPaths['resume'] ?? null,
        'tutor_style_description' => $request->tutor_style_description,
        'qualification_score'     => $finalScore,
        'recommendation_status'   => $ranking,
        'university'              => $aiData['university'],
        'course'                  => $aiData['course'],
        'ai_summary'              => $aiData['ai_summary'],

        // Safety Serialization: Converts array responses into clean JSON strings
        'experience_titles'       => json_encode($aiData['experience_titles']),
        'suggested_subjects'      => json_encode($aiData['suggested_subjects']),
    ]);

    return redirect()->route('login')->with('status', 'Application submitted successfully!');
}

    private function calculateQualificationScore(Request $request, array $finalPaths)
    {
        $score = 0;

        // 1. Experience Weight (40%)
        // Each year gives 8 points, capped at 5 years (40 points max)
        $experienceScore = min(($request->experience * 8), 40);
        $score += $experienceScore;

        // 2. Academic Weight (30%)
        $level = EducationLevel::find($request->education_level_id);
        $score += $level ? $level->weight : 0;

        // 3. CGPA Weight (20%)
        if ($request->cgpa) {
            $score += ($request->cgpa / 4.0) * 20;
        }

        // 4. Documentation Bonus (10%)
        // If both files were moved successfully, give full 10 points
        if (!empty($finalPaths['resume']) && !empty($finalPaths['tutor_cert'])) {
            $score += 10;
        }

       return (int) round($score);
    }

    //Function that also read the resume file not ponfile using pdf
    // public function store(Request $request)
    // {
    //     // 1. VALIDATION (MUST be real file upload)
    //     $request->validate([
    //         'name'              => ['required', 'string', 'max:255'],
    //         'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'age'               => ['required', 'numeric'],
    //         'address'           => ['required', 'string'],
    //         'experience'        => ['required', 'numeric'],
    //         'tutor_style'       => ['required', 'string', 'min:20'],

    //         'resume'            => ['required', 'file', 'mimes:pdf,doc,docx'],
    //         'tutor_cert'        => ['required', 'file', 'mimes:pdf,jpg,png,jpeg'],
    //         'profile_photo'     => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
    //     ]);

    //     try {

    //         /* =====================================================
    //         * 2. STORE FILES (NO TEMP, DIRECT STORAGE)
    //         * ===================================================== */
    //         $resumePath = $request->file('resume')->store('resumes', 'public');
    //         $certPath   = $request->file('tutor_cert')->store('certificates', 'public');

    //         $resumeFullPath = storage_path('app/public/' . $resumePath);

    //         /* =====================================================
    //         * 3. DEBUG CHECK (REMOVE AFTER CONFIRM WORKING)
    //         * ===================================================== */
    //         if (!file_exists($resumeFullPath)) {
    //             return back()->withErrors([
    //                 'error' => 'Resume file not found: ' . $resumeFullPath
    //             ]);
    //         }

    //         /* =====================================================
    //         * 4. SEND TO FLASK AI (FIXED MULTIPART UPLOAD)
    //         * ===================================================== */
    //         $response = Http::attach(
    //             'file',
    //             fopen($resumeFullPath, 'r'),
    //             'resume.pdf'
    //         )->post('http://127.0.0.1:50001/extract-info');

    //         $extracted = $response->successful()
    //             ? $response->json()
    //             : [
    //                 'cgpa' => 0.0,
    //                 'education_level' => 'Unknown'
    //             ];

    //         /* =====================================================
    //         * 5. HYBRID SCORING
    //         * ===================================================== */
    //         $analysis = $this->evaluateHybridApplication([
    //             'education_level' => $extracted['education_level'],
    //             'cgpa'            => $extracted['cgpa'],
    //             'experience'      => $request->experience
    //         ]);

    //         /* =====================================================
    //         * 6. CREATE USER
    //         * ===================================================== */
    //         $user = User::create([
    //             'name'     => $request->name,
    //             'email'    => $request->email,
    //             'password' => Hash::make(str()->random(16)),
    //             'role'     => 'tutor',
    //             'status'   => 'pending',
    //         ]);

    //         $user->addRole('tutor');

    //         if ($request->has('subject_expertise')) {
    //             $user->subjects()->attach($request->subject_expertise);
    //         }

    //         /* =====================================================
    //         * 7. PROFILE PHOTO
    //         * ===================================================== */
    //         $photoPath = null;

    //         if ($request->hasFile('profile_photo')) {
    //             $photoPath = $request->file('profile_photo')
    //                 ->store('photos', 'public');
    //         }

    //         /* =====================================================
    //         * 8. SAVE PROFILE
    //         * ===================================================== */
    //         TutorProfile::create([
    //             'user_id'                 => $user->id,
    //             'profile_photo'           => $photoPath,
    //             'address'                 => $request->address,
    //             'age'                     => $request->age,
    //             'experience'              => $request->experience,

    //             // AI result
    //             'education_level'         => $extracted['education_level'],
    //             'cgpa'                    => $extracted['cgpa'],
    //             'qualification_score'     => $analysis['score'],
    //             'recommendation_status'   => $analysis['ranking'],

    //             // FILES
    //             'resume'                  => $resumePath,
    //             'tutor_cert'              => $certPath,

    //             'tutor_style_description' => $request->tutor_style,
    //         ]);

    //         return redirect()->route('login')
    //             ->with('status', 'Application submitted! AI Ranking: ' . $analysis['ranking']);

    //     } catch (\Exception $e) {
    //         return back()->withErrors([
    //             'error' => 'System error: ' . $e->getMessage()
    //         ]);
    //     }
    // }

    // private function evaluateHybridApplication($data)
    // {
    //     $score = 0;

    //     // Weight 1: Education (40%)
    //     $weights = ['PhD' => 40, 'Master' => 35, 'Degree' => 25, 'Diploma' => 15];
    //     $score += $weights[$data['education_level']] ?? 10;

    //     // Weight 2: CGPA (30%) - Normalized to 4.0
    //     $score += ($data['cgpa'] / 4.0) * 30;

    //     // Weight 3: Experience (30%) - 5 points per year, cap at 6 years
    //     $score += min(($data['experience'] * 5), 30);

    //     $finalScore = round($score);

    //     // Categorization
    //     $ranking = 'Standard';
    //     if ($finalScore >= 80) $ranking = 'Highly Recommended';
    //     elseif ($finalScore >= 60) $ranking = 'Recommended';

    //     return ['score' => $finalScore, 'ranking' => $ranking];
    // }

    public function upload(Request $request)
    {
        $file=null;

        if ($request -> hasFile('tutor_cert'))
        {
            $file = $request->file('tutor_cert');
        }
        elseif ($request -> hasFile('resume'))
        {
            $file = $request ->file('resume');
        }

        if ($file)
        {
            $path = $file ->store ('tmp', 'public');
            return $path;
        }

        return response()->json(['error' => 'No file detected in request'], 400);
    }

    public function  revert(Request $request)
    {
        $filePath = $request->getContent(); // FilePond hantar path dalam body

        if ($filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                return response()->json(['status' => 'success'], 200);
            }
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
