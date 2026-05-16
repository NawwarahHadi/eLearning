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
        Schema::table('tutor_profiles', function (Blueprint $table) {
            // Storing the AI-extracted Education Level (PhD, Degree, etc.)
            $table->string('education_level')->nullable()->after('user_id');

            // Storing CGPA (3,2 means max 9.99 with 2 decimal places, e.g., 3.85)
            $table->decimal('cgpa', 3, 2)->nullable()->after('education_level');

            // Storing the System Recommendation (Highly Recommended, etc.)
            $table->string('recommendation_status')->default('Under Review')->after('qualification_score');
        });
    }

    public function down(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn(['education_level', 'cgpa','recommendation_status']);
        });
    }
};
