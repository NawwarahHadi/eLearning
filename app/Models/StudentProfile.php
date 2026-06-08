<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'age',
        'category', // e.g., Form 1, Form 2
        'profile_photo', // e.g., Form 1, Form 2
        'address',
        'exam_result',
        'student_style_description', // For AI Matching
        'preferred_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
