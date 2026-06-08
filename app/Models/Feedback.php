<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{


    protected $table = 'feedback';

    protected $fillable = [
        'student_id',
        'tutor_id',
        'class_id',
        'rating',
        'comment',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function class()
    {
        return $this->belongsTo(CreateClass::class, 'class_id');
    }
}
