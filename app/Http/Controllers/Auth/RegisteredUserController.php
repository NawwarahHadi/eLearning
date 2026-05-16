<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

// class RegisteredUserController extends Controller
// {
//     /**
//      * Display the registration view.
//      */
//     public function create(): View
//     {
//         $listSubject = Subject::all();
//         $listCategory = Category::all();

//         $data = [
//             'listSubject' => $listSubject,
//             'listCategory' => $listCategory
//         ];

//         return view('auth.register', $data);
//     }

//     /**
//      * Handle an incoming registration request.
//      *
//      * @throws ValidationException
//      */
//     public function store(Request $request): RedirectResponse
//     {
//         // 1. Validation Logic
//         $request->validate([
//             'nama_penuh'        => ['required', 'string', 'max:255'],
//             'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
//             'password'          => [
//                 'required',
//                 'confirmed',
//                 Rules\Password::min(8)
//                     ->letters()
//                     ->numbers()
//                     ->symbols()
//             ],
//             'role'              => ['required', 'in:student,tutor'],
//             'ai_description'    => ['required', 'string'],
//             'age'               => ['required', 'numeric', 'min:7'],
//             'address'           => ['required', 'string'],

//             // TUTOR ONLY FIELDS
//             'subject_expert_id' => ['required_if:role,tutor', 'nullable', 'exists:subjects,id'],
//             'experience'        => ['required_if:role,tutor', 'nullable', 'numeric'],
//             'tutor_cert'        => ['nullable', 'file', 'mimes:pdf', 'max:2048'],

//             // STUDENT ONLY FIELDS
//             'student_results'   => ['required_if:role,student', 'array'],
//             'category'          => ['required_if:role,student', 'string'],
//         ]);

//         // 2. Create the User Record
//         $user = User::create([
//             'name'              => $request->nama_penuh,
//             'nama_penuh'        => $request->nama_penuh,
//             'email'             => $request->email,
//             'password'          => Hash::make($request->password),
//             'role'              => $request->role, // String column in users table
//             'status'            => 'pending',     // Default status
//             'age'               => $request->age,
//             'address'           => $request->address,
//             'category'          => $request->category,
//             'ai_description'    => $request->ai_description,
//             'preferred_time'    => $request->role == 'student' ? $request->preferred_time : null,
//             'experience'        => $request->role == 'tutor' ? $request->experience : null,
//             'subject_expert_id' => $request->role == 'tutor' ? $request->subject_expert_id : null,
//         ]);

//         // 3. --- Laratrust Role Assignment ---
//         // This is what makes your @permission and @role checks work in the sidebar.
//         $user->addRole($request->role);

//         // 4. Handle Student Results (Nested Data)
//         if ($request->role == 'student' && $request->has('student_results')) {
//             foreach ($request->student_results as $result) {
//                 if (!empty($result['subject_id']) && isset($result['score'])) {
//                     StudentResult::create([
//                         'user_id'    => $user->id,
//                         'subject_id' => $result['subject_id'],
//                         'score'      => $result['score'],
//                     ]);
//                 }
//             }
//         }

//         // 5. Handle File Upload (Tutor Certificate)
//         if ($request->hasFile('tutor_cert')) {
//             $path = $request->file('tutor_cert')->store('certificates', 'public');
//             $user->update(['tutor_cert' => $path]);
//         }

//         // 6. Finalize Registration
//         event(new Registered($user));

//         // Note: We do NOT Auth::login($user) here because their status is 'pending'
//         return redirect()->route('login')->with('status_warning', 'Registration successful! Your account is pending admin approval.');
//     }
// }

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $listSubject = Subject::all();
        $listCategory = Category::all();

        $data = [
            'listSubject' => $listSubject,
            'listCategory' => $listCategory
        ];

        return view('auth.register', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {

        // 1. Validation Logic
        // We include fields for both the User table and the StudentProfile table
        $request->validate([
            'name'                      => ['required', 'string', 'max:255'],
            'email'                     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'                  => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'age'                       => ['required', 'integer', 'min:5'],
            'category'                  => ['required', 'string'], // e.g., Form 1, Form 2
            'address'                   => ['required', 'string'],
            'student_style_description' => ['required', 'string'], // For your AI Matching
            // 'preferred_time'            => ['nullable', 'string'],
        ]);

        // 2. Create the User (The Parent)
        // We only save login-related data here to keep the table clean
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'student',   // Hardcoded since this is the student flow
            'status'     => 'approved', // Usually students are auto-approved, unlike tutors
        ]);

        // 3. Assign Laratrust Role
        $user->addRole('student');

        // 4. Create the Student Profile (The Child)
        // This uses the relationship you just fixed in the User model
        $user->studentProfile()->create([
            'age'                       => $request->age,
            'category'                  => $request->category,
            'address'                   => $request->address,
            'student_style_description' => $request->student_style_description,
            // 'preferred_time'            => $request->preferred_time,
        ]);

        // Use 'student_results' instead of 'results'
        if ($request->has('student_results')) {
            foreach ($request->student_results as $result) {
                // Double check the fields exist
                if (!empty($result['subject_id']) && isset($result['score'])) {
                    StudentResult::create([
                        'user_id'    => $user->id,
                        'subject_id' => $result['subject_id'],
                        'score'      => $result['score'],
                    ]);
                }
            }
        }

        // 5. Fire Registered Event
        event(new Registered($user));

        // 6. Log them in and redirect to the Student Dashboard
        Auth::login($user);

        return redirect()->route('login')->with('status_warning', 'Registration successful! Your account is pending admin approval.');

    }
}
