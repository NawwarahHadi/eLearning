<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class StudentClassController extends Controller
{
    public function index()
    {
        $enrolledClasses = Enrollment::with(['class.subject', 'tutor', 'schedule'])
            ->where('student_id', Auth::id())
            ->where('status', 'approve')
            ->get()
            ->groupBy('class_id');

        return view('student.list-class', compact('enrolledClasses'));
    }

    public function showMaterials(int $class_id)
    {
        $enrollment = Enrollment::where('student_id', Auth::id())
            ->where('class_id', $class_id)
            ->with('schedule')
            ->first();

        $materialsByWeek = LearningMaterial::where('class_id', $class_id)
            ->with(['quiz', 'schedule'])
            ->orderBy('class_date')
            ->get()
            ->groupBy('week')
            ->sortBy(fn($items, $week) => (int) filter_var($week, FILTER_SANITIZE_NUMBER_INT), SORT_NUMERIC);

        return view('student.learning-materials', compact('materialsByWeek', 'class_id', 'enrollment'));
    }

    public function download(int $id, string $type)
    {
        $material = LearningMaterial::findOrFail($id);


        if ($type == 'note') {
            $pathInDb = $material->lecture_note;
            $label = 'Lecture Note';
        } elseif ($type == 'exercise') {
            $pathInDb = $material->exercise;
            $label = 'Exercise';
        } elseif ($type == 'recording') {
            $pathInDb = $material->recording_file;
            $label = 'Class Recording';
        } else {
            return redirect()->back()->with('error', 'Invalid file type requested.');
        }

        // 2. Check if file exists in storage
        if (!$pathInDb || !Storage::disk('public')->exists($pathInDb)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        // 3. Get file info
        $filePath = storage_path('app/public/' . $pathInDb);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        // 4. Create a clean name for the browser/download
        $cleanTopic = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $material->topic);
        $niceName = "Week " . $material->week . " - " . $label . " - " . ($cleanTopic ?: 'Material') . "." . $extension;

        // Use response()->file() to open in browser, or ->download() to force download
        // target="_blank" in your Blade is recommended for ->file()
        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $niceName . '"'
        ]);
    }
}
