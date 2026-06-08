<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Category;
use App\Models\EducationLevel;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $listCategory = Category::all();
        $listSubject  = Subject::all();
        $level        = EducationLevel::all();

        return view('profile.edit', [
            'user' => $user,
            'listCategory' => $listCategory,
            'listSubject' => $listSubject,
            'level' => $level,
        ]);

    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Core global validation rules shared by everyone
        $rules = [
            'name'          => ['required', 'string', 'max:255'],
            'phone_number'  => ['required', 'string', 'max:20'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'age'           => ['required', 'integer', 'min:5', 'max:100'],
            'address'       => ['required', 'string', 'max:500'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB file size
        ];

        // 2. Append role-specific inputs on the fly
        if ($user->role === 'student') {
            $rules['category']                  = ['required', 'string'];
            $rules['student_style_description'] = ['nullable', 'string', 'max:1000'];
        } elseif ($user->role === 'tutor') {
            $rules['subject_expertise']         = ['required', 'array'];
            $rules['subject_expertise.*']       = ['integer'];
            $rules['tutor_style_description']   = ['required', 'string', 'max:1000'];
            $rules['experience']                = ['required', 'integer', 'min:0', 'max:50'];
            $rules['cgpa']                      = ['required', 'numeric', 'between:0.00,4.00'];
            $rules['education_level_id']        = ['required', 'integer'];
        }

        $validated = $request->validate($rules);

        // 3. Wrap everything in a transaction so both tables stay completely synchronized
        DB::transaction(function () use ($request, $user, $validated) {

            // Handle Profile Image Upload based on role table configurations
            if ($request->hasFile('profile_photo')) {

                if ($user->role === 'student') {
                    // Students save directly to the users table column
                    if ($user->profile_photo) {
                        Storage::disk('public')->delete($user->profile_photo);
                    }
                    $user->profile_photo = $request->file('profile_photo')->store('profile_photos/students', 'public');

                } elseif ($user->role === 'tutor') {
                    // Tutors save to the related tutor_profile table column
                    $tutorProfile = $user->tutorProfile; // Retrieves the related profile row

                    if ($tutorProfile && $tutorProfile->profile_photo) {
                        Storage::disk('public')->delete($tutorProfile->profile_photo);
                    }

                    $path = $request->file('profile_photo')->store('profile_photos/tutors', 'public');

                    // Set it on the relation instead
                    $user->tutorProfile()->update(['profile_photo' => $path]);
                }
            }

            // 4. Update core shared profile values on the Users table
            $user->update([
                'name'         => $validated['name'],
                'phone_number' => $validated['phone_number'],
                'email'        => $validated['email'],
                'age'          => $validated['age'],
                'address'      => $validated['address'],
                // Student fields if user is a student
                'category'     => $user->role === 'student' ? $validated['category'] : $user->category,
                'student_style_description' => $user->role === 'student' ? ($validated['student_style_description'] ?? null) : $user->student_style_description,
            ]);

            // 5. Update custom Tutor Profile table properties if user is a Tutor
            if ($user->role === 'tutor') {
                $user->tutorProfile()->update([
                    'subject_expertise'       => json_encode($validated['subject_expertise']),
                    'tutor_style_description' => $validated['tutor_style_description'],
                    'experience'              => $validated['experience'],
                    'cgpa'                    => $validated['cgpa'],
                    'education_level_id'      => $validated['education_level_id'],
                ]);
            }
        });

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
