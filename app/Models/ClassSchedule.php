<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $table = 'class_schedules';

    protected $fillable = [
        'class_id',
        'tutor_id', // 🌟 Ensure tutor_id is here since you call $makeup->tutor->name
        'day',
        'start_time',
        'end_time',
        'is_temporary',
        'reschedule_student_id',
        'reschedule_reason',
        'zoom_link'
    ];

    /**
     * Keep your original relationship method
     */
    public function parentClass(): BelongsTo
    {
        return $this->belongsTo(CreateClass::class, 'class_id');
    }

    /**
     * 🌟 FIX: Add this relationship so your Dashboard View works perfectly
     * without throwing the 'undefined relationship' crash!
     */
    public function classModule(): BelongsTo
    {
        return $this->belongsTo(CreateClass::class, 'class_id');
    }

    /**
     * 🌟 ADD THIS: Relationship to load the Tutor details for the dashboard list
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
