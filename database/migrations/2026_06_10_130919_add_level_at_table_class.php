<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_level_to_class_table.php
    public function up()
    {
        Schema::table('class', function (Blueprint $table) {
            $table->enum('level', ['low', 'medium', 'good'])->default('medium')->after('subject_id');
        });
    }

    public function down()
    {
        Schema::table('class', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
