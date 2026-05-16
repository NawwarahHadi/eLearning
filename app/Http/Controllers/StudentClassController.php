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
            ->where('status', 'approved')
            ->get()
            ->groupBy('class_id'); // This groups multiple schedules into one row

        return view('student.list-class', compact('enrolledClasses'));
    }

    public function showMaterials(int $class_id)
    {
        $enrollment = Enrollment::where('student_id', Auth::id())
        ->where('class_id', $class_id)
        ->with('schedule') // Load the ClassSchedule model
        ->first();

        $materials = LearningMaterial::where('class_id', $class_id)
            ->with('quiz')
            ->get();

        return view('student.learning-materials', compact('materials', 'class_id', 'enrollment'));
    }

    public function download(int $id, string $type)
    {
        $material = LearningMaterial::findOrFail($id);

        // 1. Map the type to the correct database column and label
        if ($type == 'note') {
            $pathInDb = $material->lecture_note;
            $label = 'Lecture Note';
        } elseif ($type == 'exercise') {
            $pathInDb = $material->exercise;
            $label = 'Exercise';
        } elseif ($type == 'recording') { // Added Recording File logic
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
