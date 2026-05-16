<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAssessment extends Model
{
   protected $fillable = [
        'student_id', 'tutor_id', 'class_id',
        'plo1_score', 'plo2_score', 'content_relevance',
        'content_updated', 'delivery_elearn', 'delivery_facilities',
        'assess_continuous', 'assess_load', 'overall_comments'
    ];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor() {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
