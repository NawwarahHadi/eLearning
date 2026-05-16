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
    Schema::create('enrollments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('student_id');
        $table->unsignedBigInteger('class_id');
        $table->unsignedBigInteger('tutor_id');
        $table->unsignedBigInteger('schedule_id');
        $table->string('status')->default('pending');
        $table->timestamps();
        $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('class_id')->references('id')->on('class')->onDelete('cascade');
        $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('schedule_id')->references('id')->on('class_schedules')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
