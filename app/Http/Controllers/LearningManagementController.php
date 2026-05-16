<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LearningManagementController extends Controller
{
    public function index(int $class_id)
    {
        $listMaterials = LearningMaterial::where('class_id', $class_id)
            ->orderBy('week', 'asc')
            ->get();

        return view('learning-management.index', compact('listMaterials', 'class_id'));
    }

    public function create(int $class_id)
    {
        $data = [
            'learningMaterial' => new LearningMaterial(),
            'class_id'         => $class_id,
        ];

        return view('learning-management.create', $data);
    }

    /**
     * Menyimpan data dan memindahkan fail dari folder 'tmp' ke folder kekal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id'   => 'required',
            'week'       => 'required|string',
            'class_date' => 'required|date',
            'webex_link' => 'nullable|string', // Jangan guna 'url' di sini jika anda mahu cuci manual
        ]);

        $data = $request->all();
        if (!$request->webex_link) {
        $responseToken = Http::asForm()
            ->withBasicAuth(config('services.zoom.client_id'), config('services.zoom.client_secret'))
            ->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id=" . config('services.zoom.account_id'));

        if ($responseToken->successful()) {
            $token = $responseToken->json()['access_token'];

            // B. CIPTA MEETING BARU
            $responseMeeting = Http::withToken($token)->post('https://api.zoom.us/v2/users/me/meetings', [
                'topic' => "Class: " . $request->topic,
                'type' => 2, // Scheduled meeting
                'start_time' => $request->class_date . 'T20:00:00', // Contoh 8 Malam
                'duration' => 120, // 2 jam
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => true,
                ]
            ]);

            if ($responseMeeting->successful()) {
                $meeting = $responseMeeting->json();
                $data['webex_link'] = $meeting['join_url']; // Link Zoom
                $data['webex_meeting_code'] = $meeting['id'];
                $data['webex_passcode'] = $meeting['password'];
            }
        }
    }

        $folderPath = "materials/class_" . $request->class_id;

        // Pastikan folder destinasi wujud dalam storage/app/public
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // 1. Proses Pindah Fail Lecture Note
        if ($request->lecture_note && strpos($request->lecture_note, 'tmp/') === 0) {
            $oldPath = $request->lecture_note;
            $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
            $newFileName = "W" . str_replace(' ', '', $request->week) . "_Note_" . time() . "." . $extension;
            $newPath = $folderPath . "/" . $newFileName;

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $newPath);
                $data['lecture_note'] = $newPath; // Simpan path kekal dalam DB
            }
        }

        if ($request->exercise && strpos($request->exercise, 'tmp/') === 0) {
            $oldPath = $request->exercise;
            $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
            $newFileName = "W" . str_replace(' ', '', $request->week) . "_Ex_" . time() . "." . $extension;
            $newPath = $folderPath . "/" . $newFileName;

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $newPath);
                $data['exercise'] = $newPath;
            }
        }

        LearningMaterial::create($data);

        return redirect()->route('learning-material.index', $request->class_id)
                         ->with('success', 'Materials successfully saved and moved from temporary storage!');
    }

    /**
     * Fungsi Upload Asynchronous untuk FilePond (Simpan di folder tmp)
     */
    public function upload(Request $request)
    {
        $file = null;

        // Check all possible input names from FilePond
        if ($request->hasFile('lecture_note')) {
            $file = $request->file('lecture_note');
        } elseif ($request->hasFile('exercise')) {
            $file = $request->file('exercise');
        } elseif ($request->hasFile('recording_file')) { // ADD THIS LINE
            $file = $request->file('recording_file');
        }

        if ($file) {
            $path = $file->store('tmp', 'public');
            return $path;
        }

        // If it reaches here, Laravel didn't "see" the file in the request
        return response()->json(['error' => 'No file detected in request'], 400);
    }

    /**
     * Fungsi Revert untuk memadam fail di folder tmp jika tutor tekan 'X'
     */
    public function revert(Request $request)
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

    public function destroy(int $id)
    {
        $material = LearningMaterial::findOrFail($id);

        // Padam fail fizikal sebelum padam rekod DB
        if ($material->lecture_note) Storage::disk('public')->delete($material->lecture_note);
        if ($material->exercise) Storage::disk('public')->delete($material->exercise);

        $class_id = $material->class_id;
        $material->delete();

        return redirect()->route('learning-material.index', $class_id)->with('success', 'Material deleted.');
    }

    public function download(int $id, string $type)
    {
        $material = LearningMaterial::findOrFail($id);

        // Pilih path berdasarkan type (note atau exercise)
        $pathInDb = ($type == 'note') ? $material->lecture_note : $material->exercise;
        $label = ($type == 'note') ? 'Lecture Note' : 'Exercise';

        if (!$pathInDb || !Storage::disk('public')->exists($pathInDb)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        $filePath = storage_path('app/public/' . $pathInDb);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        // BINA NAMA FAIL: "Week 2 - Exercise - EA Concept.pdf"
        $cleanTopic = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $material->topic);
        $niceName = $material->week . " - " . $label . " - " . ($cleanTopic ?: 'Material') . "." . $extension;

        return response()->download($filePath, $niceName);
    }

    public function edit(int $id)
    {
        $learningMaterial = LearningMaterial::findOrFail($id);
        $class_id = $learningMaterial->class_id;

        return view('learning-management.edit', compact('learningMaterial', 'class_id'));
    }

    public function update(Request $request, $id)
    {
        $material = LearningMaterial::findOrFail($id);

        $request->validate([
            'week'       => 'required',
            'class_date' => 'required|date',
            'topic'      => 'required'
        ]);

        $data = $request->except(['lecture_note', 'exercise', 'recording_file']);
        $folderPath = "materials/class_" . $material->class_id;

        // Array of files to check for updates
        $fileFields = [
            'lecture_note'   => 'Note',
            'exercise'       => 'Ex',
            'recording_file' => 'Video'
        ];

        foreach ($fileFields as $field => $prefix) {
            // Only process if a NEW file was uploaded to the 'tmp' folder
            if ($request->$field && strpos($request->$field, 'tmp/') === 0) {

                // 1. Delete the old file from storage if it exists
                if ($material->$field) {
                    Storage::disk('public')->delete($material->$field);
                }

                // 2. Move the new file to the permanent folder
                $oldPath = $request->$field;
                $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                $newFileName = "W" . $request->week . "_{$prefix}_" . time() . "." . $extension;
                $newPath = $folderPath . "/" . $newFileName;

                Storage::disk('public')->move($oldPath, $newPath);
                $data[$field] = $newPath;
            }
        }

        $material->update($data);

        return redirect()->route('learning-material.index', $material->class_id)
                         ->with('success', 'Learning materials updated successfully!');
    }

    public function openFile(int $id, string $type)
    {
        $material = LearningMaterial::findOrFail($id);

        // Identify which file path to use
        $pathInDb = ($type == 'note') ? $material->lecture_note : $material->exercise;

        if (!$pathInDb || !Storage::disk('public')->exists($pathInDb)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        // Get the absolute path
        $filePath = Storage::disk('public')->path($pathInDb);

        // response()->file() opens it in the browser
        return response()->file($filePath);
    }
    public function deleteSingleFile(int $id, string $type)
    {
        $material = LearningMaterial::findOrFail($id);
        $column = '';

        // Determine which column to clear
        if ($type == 'note') {
            $column = 'lecture_note';
        } elseif ($type == 'exercise') {
            $column = 'exercise';
        } elseif ($type == 'recording') {
            $column = 'recording_file';
        }

        // If the column exists and has a file path
        if ($column && $material->$column) {
            // 1. Delete the physical file from storage
            Storage::disk('public')->delete($material->$column);

            // 2. Update the database column to NULL
            $material->update([
                $column => null
            ]);

            return redirect()->back()->with('success', 'File deleted successfully.');
        }

        return redirect()->back()->with('error', 'File not found.');
    }
}
