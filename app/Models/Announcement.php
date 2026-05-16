<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    // Explicitly define the table name if it's not "announcements"
    protected $table = 'announcements';

    /**
     * The attributes that are mass assignable.
     * These must match the 'name' attributes in your _form.blade.php
     */
    protected $fillable = [
        'title',
        'description',
        'user_id', // This tracks who created the announcement
    ];

    /**
     * Relationship: An announcement belongs to a User (Admin/Tutor)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
