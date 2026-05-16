<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Quizzes Header
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            // Links to 'id' in 'class' table
            $table->foreignId('class_id')->constrained('class')->onDelete('cascade');
            // Links to 'id' in 'users' table (specifically for tutors)
            $table->unsignedBigInteger('tutor_id');
            $table->string('title');
            $table->timestamps();

            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Questions
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->char('correct_option', 1); // A, B, C, or D
            $table->timestamps();
        });

        // 3. Results (Separate from your academic student_results)
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->unsignedBigInteger('student_id'); // Links to 'id' in 'users'
            $table->integer('score');
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_system_tables');
    }
};
