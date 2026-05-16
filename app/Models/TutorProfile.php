<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'profile_photo',
        'address',
        'age',
        'experience',
        'tutor_cert',
        'resume',
        'tutor_style_description',
        'education_level_id',
        'cgpa',
        'qualification_score',
        'recommendation_status',
        'university',
        'course',
        'experience_titles',
        'suggested_subjects',
        'ai_summary',
    ];

    protected $casts = [
    'experience_titles' => 'array',
    'suggested_subjects' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educationLevel()
    {
        // This tells Laravel that education_level_id belongs to the EducationLevel model
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }
}
