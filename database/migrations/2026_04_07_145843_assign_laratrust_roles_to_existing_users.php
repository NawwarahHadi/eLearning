<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Get the role objects from Laratrust
        $studentRole = Role::where('name', 'student')->first();
        $tutorRole   = Role::where('name', 'tutor')->first();

        // Get all users who don't have a Laratrust role yet
        $users = User::all();

        foreach ($users as $user) {
            // Check the existing string 'role' column in your users table
            if ($user->role == 'student' && $studentRole) {
                // This adds the entry to the role_user pivot table
                if (!$user->hasRole('student')) {
                    $user->addRole($studentRole);
                }
            }
            elseif ($user->role == 'tutor' && $tutorRole) {
                if (!$user->hasRole('tutor')) {
                    $user->addRole($tutorRole);
                }
            }
        }
    }

    public function down(): void
    {
        // Optional: Logic to remove roles if you rollback
    }
};
