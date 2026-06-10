<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model {
    protected $fillable = ['class_id', 'tutor_id', 'learning_material_id', 'title'];

    public function questions() {
        return $this->hasMany(QuizQuestion::class);
    }

    public function learningMaterial()
    {
        return $this->belongsTo(LearningMaterial::class, 'learning_material_id');
    }

    public function attempts() {
        return $this->hasMany(QuizAttempt::class);
    }

    // Added: link back to the class (table is 'class', model CreateClass)
    public function createClass()
    {
        return $this->belongsTo(CreateClass::class, 'class_id');
    }
}
