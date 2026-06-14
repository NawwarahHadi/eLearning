<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\Subject;
use App\Models\User;
use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TutorRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.register-tutor', [
            'listSubject' => Subject::all(),
            'level'       => EducationLevel::all(),
        ]);
    }

    // ═════════════════════════════════════════════════════════
    // MAIN STORE — full flow: files → score → parse → summary
    // ═════════════════════════════════════════════════════════
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

        // ── 1. Create User (pending) ──
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password ?? Str::random(16)),
            'role'     => 'tutor',
            'status'   => 'pending',
        ]);
        $user->addRole('tutor');

        // ── 2. Profile photo ──
        $photoPath = $request->hasFile('profile_photo')
            ? $request->file('profile_photo')->store('photos', 'public')
            : null;

        // ── 3. Move resume + cert files ──
        $finalPaths = [];
        $fileFields = ['resume' => 'resumes', 'tutor_cert' => 'certificates'];

        foreach ($fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $finalPaths[$field] = $request->file($field)->store($folder, 'public');
            } else {
                $tmpPath = $request->input($field);
                if ($tmpPath && Storage::disk('public')->exists($tmpPath)) {
                    $newPath = "$folder/" . basename($tmpPath);
                    Storage::disk('public')->move($tmpPath, $newPath);
                    $finalPaths[$field] = $newPath;
                }
            }
        }

        // ── 4. Calculate qualification score + reasons ──
        $evaluation = $this->calculateQualificationScore($request, $finalPaths);
        $finalScore = $evaluation['score'];
        $ranking    = $evaluation['ranking'];
        $reasons    = $evaluation['reasons'];

        // ── 5. Parse resume with Gemini + generate summary ──
        $aiData = [
            'university'         => 'Not Extracted',
            'course'             => 'Not Extracted',
            'ai_summary'         => 'No summary generated.',
            'experience_titles'  => [], // Set default as native PHP array
            'suggested_subjects' => [], // Set default as native PHP array
        ];

        if (isset($finalPaths['resume'])) {
            $resumeText = $this->extractPdfText(
                storage_path("app/public/" . $finalPaths['resume'])
            );

            if (!empty($resumeText)) {
                $parsed = $this->parseResumeWithGemini($resumeText, $finalScore, $ranking, $reasons);

                if ($parsed) {
                    $aiData['university']         = $parsed['university'] ?? 'Not Extracted';
                    $aiData['course']             = $parsed['course'] ?? 'Not Extracted';
                    $aiData['ai_summary']         = $parsed['summary'] ?? 'No summary generated.';
                    $aiData['experience_titles']  = $parsed['experience_titles'] ?? [];
                    $aiData['suggested_subjects'] = $parsed['suggested_subjects'] ?? [];
                }
            }
        }

        // ── 6. Attach subjects ──
        if ($request->has('subject_expertise')) {
            $user->subjects()->attach($request->subject_expertise);
        }

        // ── 7. Save tutor profile (Handing down array items cleanly) ──
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
            'experience_titles'       => $aiData['experience_titles'],  // Handled smoothly by Model Cast
            'suggested_subjects'      => $aiData['suggested_subjects'], // Handled smoothly by Model Cast
        ]);

        return redirect()->route('login')
            ->with('status', 'Application submitted successfully! Admin will review your qualification soon.');
    }

    private function calculateQualificationScore(Request $request, array $finalPaths)
    {
        $score   = 0;
        $reasons = [];

        // Experience (40%)
        $expScore = min(($request->experience * 8), 40);
        $score   += $expScore;

        if ($request->experience < 2) {
            $reasons[] = "Limited teaching experience ({$request->experience} year(s)). Under 2 years scores low on the experience component (40% weight).";
        } elseif ($request->experience >= 5) {
            $reasons[] = "Strong experience ({$request->experience}+ years) earned full marks on experience.";
        }

        // Education level (30%)
        $level       = EducationLevel::find($request->education_level_id);
        $levelWeight = $level ? $level->weight : 0;
        $score      += $levelWeight;

        if ($levelWeight < 15) {
            $reasons[] = "Education level (" . ($level->name ?? 'Unknown') . ") gives a lower academic weight.";
        } elseif ($levelWeight >= 25) {
            $reasons[] = "High education level (" . ($level->name ?? '') . ") boosted the academic score.";
        }

        // CGPA (20%)
        if ($request->cgpa) {
            $score += ($request->cgpa / 4.0) * 20;
            if ($request->cgpa < 2.5) {
                $reasons[] = "CGPA of {$request->cgpa} is below 2.50, lowering academic performance.";
            } elseif ($request->cgpa >= 3.5) {
                $reasons[] = "Excellent CGPA ({$request->cgpa}) added strongly to the score.";
            }
        } else {
            $reasons[] = "No CGPA provided — missed the 20% academic component.";
        }

        // Documents (10%)
        if (!empty($finalPaths['resume']) && !empty($finalPaths['tutor_cert'])) {
            $score += 10;
        } else {
            $missing = [];
            if (empty($finalPaths['resume']))     $missing[] = 'resume';
            if (empty($finalPaths['tutor_cert'])) $missing[] = 'certificate';
            $reasons[] = "Missing document(s): " . implode(', ', $missing) . " — lost the 10% documentation bonus.";
        }

        $finalScore = (int) round($score);

        $ranking = 'Standard';
        if ($finalScore >= 80)      $ranking = 'Highly Recommended';
        elseif ($finalScore >= 60)  $ranking = 'Recommended';

        if ($finalScore < 60) {
            array_unshift($reasons, "Scored {$finalScore}/100, below the 60-point recommendation threshold. Areas to review:");
        }

        return ['score' => $finalScore, 'ranking' => $ranking, 'reasons' => $reasons];
    }

    private function extractPdfText($pdfPath)
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            return $parser->parseFile($pdfPath)->getText();
        } catch (\Exception $e) {
            Log::error('PDF parse failed: ' . $e->getMessage());
            return '';
        }
    }

    private function parseResumeWithGemini($resumeText, $score, $ranking, array $reasons)
    {
        $apiKey = env('GEMINI_API_KEY');
        $model  = 'gemini-2.5-flash';

        $reasonText = implode(' ', $reasons);

        $prompt = "You are an HR assistant for a tuition centre evaluating a tutor applicant. " .
            "From the resume text, extract data and write a professional summary. " .
            "Return ONLY valid JSON with these exact keys:\n" .
            "- name (string)\n" .
            "- university (string)\n" .
            "- course (string)\n" .
            "- experience_titles (array of job titles found)\n" .
            "- suggested_subjects (array, ONLY from this list: Additional Mathematics, Mathematics, Physics, Chemistry, English, Science, Accounting)\n" .
            "- summary (string, 3-4 sentences). The applicant scored {$score}/100 and is ranked '{$ranking}'. " .
            "Score analysis: {$reasonText} " .
            "In the summary, describe the applicant's strengths, then if the score is below 60, clearly explain " .
            "which areas were weak and why they were not recommended.\n\n" .
            "Resume text:\n" . $resumeText;

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents'         => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature'      => 0.3,
                        'responseMimeType' => 'application/json',
                    ],
                ]
            );

            if ($response->successful()) {
                $text  = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
                $clean = preg_replace('/```json|```/', '', $text);
                $parsed = json_decode(trim($clean), true);

                // Deep unwrap parameters if Gemini outputs nested json strings inside properties
                if (isset($parsed['suggested_subjects']) && is_string($parsed['suggested_subjects'])) {
                    $parsed['suggested_subjects'] = json_decode($parsed['suggested_subjects'], true) ?? [];
                }
                if (isset($parsed['experience_titles']) && is_string($parsed['experience_titles'])) {
                    $parsed['experience_titles'] = json_decode($parsed['experience_titles'], true) ?? [];
                }

                return $parsed;
            }

            Log::error('Gemini API error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Gemini request failed: ' . $e->getMessage());
        }

        return null;
    }

    public function upload(Request $request)
    {
        $file = $request->file('tutor_cert') ?? $request->file('resume');
        if ($file) {
            return $file->store('tmp', 'public');
        }
        return response()->json(['error' => 'No file detected'], 400);
    }

    public function revert(Request $request)
    {
        $filePath = $request->getContent();
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => 'File not found'], 404);
    }

    public function show(int $id)
    {
        $tutor = \App\Models\TutorProfile::with('user')
            ->where('user_id', $id)
            ->firstOrFail();

        return view('application.tutor-application', compact('tutor'));
    }
}
