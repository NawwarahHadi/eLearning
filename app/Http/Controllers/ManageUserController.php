<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ManageUserController extends Controller
{
    public function index()
    {
        $students = User::with('studentProfile')->where('role', 'student')->latest()->get();
        $tutors   = User::with('tutorProfile')
            ->where('role', 'tutor')
            ->where('status', 'approved')
            ->latest()
            ->get();
        $admins   = User::where('role', 'admin')->latest()->get();

        return view('manage-user.index', compact('students', 'tutors', 'admins'));
    }

    public function create()
    {
        return view('manage-user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,student,tutor',
            'status'   => 'required|in:pending,approved,rejected',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
        ]);

        Alert::success('Success', 'User created successfully.');
        return redirect()->route('user-management.index');
    }

    public function edit(int $id)
    {
        $user = User::with([
            'studentProfile',
            'tutorProfile',
            'results.subject',
        ])->findOrFail($id);

        return view('manage-user.edit', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'phone_number'  => $request->phone_number,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($user->role === 'student' && $user->studentProfile) {
            $user->studentProfile->update([
                'age'                       => $request->age,
                'category'                  => $request->category,
                'address'                   => $request->address,
                'student_style_description' => $request->student_style_description,
            ]);
        }

        if ($user->role === 'tutor' && $user->tutorProfile) {
            $user->tutorProfile->update([
                'age'                     => $request->age,
                'experience'              => $request->experience,
                'address'                 => $request->address,
                'university'              => $request->university,
                'course'                  => $request->course,
                'tutor_style_description' => $request->tutor_style_description,
            ]);
        }

        Alert::success('Success', 'User updated successfully.');
        return redirect()->route('user-management.index');
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            Alert::error('Error', 'Cannot delete admin user.');
            return redirect()->route('user-management.index');
        }

        $user->delete();

        Alert::success('Success', 'User deleted successfully.');
        return redirect()->route('user-management.index');
    }

    public function loginAs(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            Alert::error('Error', 'Cannot login as another admin.');
            return back();
        }

        session(['impersonator_id' => Auth::id()]);
        Auth::loginUsingId($id);

        Alert::success('Logged In', 'You are now logged in as ' . $user->name);

        if ($user->role === 'student') {
            return redirect()->route('dashboard.student');
        } elseif ($user->role === 'tutor') {
            return redirect()->route('dashboard.tutor');
        }

        return redirect()->route('dashboard.admin');
    }

    public function stopImpersonating()
    {
        $adminId = session('impersonator_id');

        if (!$adminId) {
            return redirect()->route('dashboard.admin');
        }

        Auth::loginUsingId($adminId);
        session()->forget('impersonator_id');

        Alert::success('Success', 'Returned to admin account.');
        return redirect()->route('user-management.index');
    }

    public function setPassword(int $id)
    {
        $user = User::findOrFail($id);

        $tempPassword = 'tuition@' . substr($user->email, 0, 4) . rand(100, 999);

        $user->update([
            'password' => Hash::make($tempPassword),
        ]);

        Alert::success('Temporary Password Set', 'Password for ' . $user->name . ': ' . $tempPassword)
            ->persistent(true, false);

        return back();
    }
}
