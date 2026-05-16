<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Roles
        $superadmin = Role::create(['name' => 'superadmin', 'display_name' => 'Superadmin']);
        $admin      = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $tutor      = Role::create(['name' => 'tutor', 'display_name' => 'Tutor']);
        $student    = Role::create(['name' => 'student', 'display_name' => 'Student']);

        // 2. Define Permissions

        // Admin Permissions
        $p1 = Permission::create(['name' => 'manage-applications', 'display_name' => 'Manage Applications']);
        $p2 = Permission::create(['name' => 'manage-enrollment', 'display_name' => 'Manage Student Enrollment']);

        // Tutor Permissions
        $p3 = Permission::create(['name' => 'manage-classes', 'display_name' => 'Manage Classes']);

        // Shared (Admin & Tutor)
        $p4 = Permission::create(['name' => 'manage-announcements', 'display_name' => 'Manage Announcements']);

        // Student Permissions
        $p5 = Permission::create(['name' => 'enroll-class', 'display_name' => 'Enroll in Class']);
        $p6 = Permission::create(['name' => 'view-class-content', 'display_name' => 'View Class Content']);

        // 3. Assign Permissions to Roles

        // Admin gets apps, enrollment, and announcements
        $admin->givePermissions([$p1, $p2, $p4]);

        // Tutor gets class management and announcements
        $tutor->givePermissions([$p3, $p4]);

        // Student gets enrollment and viewing classes
        $student->givePermissions([$p5, $p6]);

        // Note: Superadmin doesn't need permissions assigned if you use the Gate::before logic
        // in AuthServiceProvider, but you can assign them all here if you prefer.
    }
}
