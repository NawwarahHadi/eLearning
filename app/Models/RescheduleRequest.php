<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RescheduleRequest extends Model
{
    /**
     * The table associated with the model.
     * Optional: Laravel assumes 'reschedule_requests' automatically,
     * but defining it explicitly ensures stability across setups.
     */
    protected $table = 'reschedule_requests';

    /**
     * The attributes that are mass assignable.
     * Protects against MassAssignmentExceptions when saving from the controller.
     */
    protected $fillable = [
        'class_id',
        'tutor_id',
        'student_id',
        'reason',
        'proposed_time',
        'status', // pending, approved, rejected
    ];

    /**
     * Get the Class Module instance associated with this proposal.
     */
    public function classModule(): BelongsTo
    {
        // Adjust 'class_id' or 'class' if your base table uses a custom primary key name
        return $this->belongsTo(CreateClass::class, 'class_id');
    }

    /**
     * Get the Student account record who submitted the request.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the Tutor account record assigned to handle this request.
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
