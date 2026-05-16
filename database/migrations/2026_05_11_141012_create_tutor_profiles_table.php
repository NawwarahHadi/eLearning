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
        Schema::create('tutor_profiles', function (Blueprint $table) {
            $table->id();
            // This links to users table and handles the 'drop' error you got
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('profile_photo')->nullable();
            $table->text('address')->nullable(); //
            $table->integer('age')->nullable(); //
            $table->integer('experience')->default(0); //
            $table->string('tutor_cert')->nullable(); //
            $table->string('resume')->nullable();

            // This is for your Flask AI matching [cite: 110]
            $table->text('tutor_style_description');

            // Your Supervisor's requested scoring column
            $table->integer('qualification_score')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_profiles');
    }
};
