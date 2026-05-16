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
        Schema::create('learning_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('class')->onDelete('cascade');
            $table->integer('week');
            $table->date('class_date');
            $table->string('webex_link')->nullable();
            $table->string('webex_meeting_code')->nullable();
            $table->string('webex_passcode')->nullable();
            $table->string('lecture_note')->nullable();
            $table->string('exercise')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_materials');
    }
};
