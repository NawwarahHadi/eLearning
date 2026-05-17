<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            // If this is true, it's a temporary 1-to-1 makeup class
            $table->boolean('is_temporary')->default(false);

            // Lock this temporary slot to the specific student who requested it
            $table->foreignId('reschedule_student_id')->nullable()->constrained('users')->onDelete('cascade');

            // Store their reason for documentation/admin review
            $table->string('reschedule_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            //
        });
    }
};
