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
        Schema::table('quizzes', function (Blueprint $table) {
            // 1. Add the column after the tutor_id column
            $table->unsignedBigInteger('learning_material_id')->nullable()->after('tutor_id');

            // 2. Set up the foreign key relationship
            $table->foreign('learning_material_id')
                ->references('id')
                ->on('learning_materials')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Drop the foreign key and column if we rollback
            $table->dropForeign(['learning_material_id']);
            $table->dropColumn('learning_material_id');
        });
    }
};
