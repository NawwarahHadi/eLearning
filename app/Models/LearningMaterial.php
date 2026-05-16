<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    protected $table = "learning_materials";

    protected $fillable = [
        'class_id',
        'week',
        'topic',
        'class_date',
        'webex_link',
        'webex_meeting_code',
        'webex_passcode',
        'lecture_note',
        'exercise',
        'recording_file'
    ];

    public function tutorClass() {
        return $this->belongsTo(CreateClass::class, 'class_id');
    }

    public function quiz()
    {
        // One material can have one quiz
        return $this->hasOne(Quiz::class, 'learning_material_id');
    }
}
