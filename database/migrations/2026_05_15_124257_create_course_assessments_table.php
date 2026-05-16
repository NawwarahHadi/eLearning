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
       Schema::create('course_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('tutor_id')->constrained('users');
            $table->foreignId('class_id')->constrained('class');

            // Section 1: Learning Outcomes (PLO)
            $table->integer('plo1_score');
            $table->integer('plo2_score');

            // Section 2: Course Content
            $table->integer('content_relevance');
            $table->integer('content_updated');

            // Section 3: Delivery
            $table->integer('delivery_elearn');
            $table->integer('delivery_facilities');

            // Section 4: Implementation
            $table->integer('assess_continuous');
            $table->integer('assess_load');

            // Section 5: Final Comment
            $table->text('overall_comments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_assessments');
    }
};
