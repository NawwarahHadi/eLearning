<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'evaluations';

    // Ruangan yang dibenarkan untuk disimpan (Mass Assignment)
    protected $fillable = [
        'student_id',
        'class_id',
        'tutor_id',
        'progress_level',
        'understanding_score',
        'participation_score',
        'homework_score',
        'overall_score',
        'comments',
    ];
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function createClass()
    {
        return $this->belongsTo(CreateClass::class, 'class_id');
    }
}
