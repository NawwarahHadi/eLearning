<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model {
    protected $fillable = ['class_id', 'tutor_id','learning_material_id', 'title'];

    public function questions() {
        return $this->hasMany(QuizQuestion::class);
    }

    public function learningMaterial()
    {
        // The quiz belongs to a specific material
        return $this->belongsTo(LearningMaterial::class, 'learning_material_id');
    }



    public function attempts() {
        // A quiz can have many attempts from different students
        return $this->hasMany(QuizAttempt::class);
    }

}
