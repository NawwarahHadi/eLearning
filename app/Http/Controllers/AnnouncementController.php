<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        // Get all announcements, newest first
        $announcementList = Announcement::latest()->get();

        $data = [
            'announcementList' => $announcementList,
        ];

        return view('announcement.index', $data);
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        // Only Admin and Tutor can access the create page
        if (Auth::user()->role == 'student') {
            abort(403, 'Unauthorized access.');
        }

        $data = [
            'announcement' => new Announcement(),
        ];

        return view('announcement.create', $data);
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required'],
        ]);


        $announcement = Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        // ($announcement)
        //     ? Alert::success('Success', 'Announcement saved successfully.')
        //     : Alert::error('Error', 'Failed to save announcement.');

        return redirect()->route('announcement.index');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(string $id)
    {
        // Only Admin and Tutor can access the edit page
        if (Auth::user()->role == 'student') {
            abort(403);
        }

        $announcement = Announcement::find($id);

        return view('announcement.update', ['announcement' => $announcement]);
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required'],
        ]);

        $announcement = Announcement::find($id);

        $status = $announcement->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // ($status)
            // ? Alert::success('Success', 'Announcement updated successfully.')
            // : Alert::error('Error', 'Failed to update announcement.');

        return redirect()->route('announcement.index');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(string $id)
    {
        // Only Admin and Tutor can delete
        if (Auth::user()->role == 'student') {
            abort(403);
        }

        $announcement = Announcement::find($id);
        $delete = $announcement->delete();

        // ($delete)
            // ? Alert::success('Success', 'Announcement deleted successfully.')
            // : Alert::error('Error', 'Failed to delete announcement.');

        return redirect()->route('announcement.index');
    }
}
