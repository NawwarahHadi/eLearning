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
        Schema::create('class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('tutor_id')->constrained('users'); // Role tutor
            $table->string('category_code');
            $table->string('language_code');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('fee', 8, 2);
            $table->integer('max_students');
            $table->string('mode')->default('online');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_classes');
    }
};
