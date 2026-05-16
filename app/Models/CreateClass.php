<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreateClass extends Model
{
    use HasFactory;

    // Explicitly define the table name because 'class' is a reserved word
    protected $table = 'class';

    // Allow these fields to be filled via the create() method
    protected $fillable = [
        'subject_id',
        'tutor_id',
        'category_code',
        'language_code',
        'learning_objective',
        'fee',
        'max_students',
        'hours_per_week',
    ];

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function tutor()
    {
        // connect tutor with class
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function category()
    {
        // Hubungan ini merujuk category_code dalam table class ke code dalam table category
        return $this->belongsTo(Category::class, 'category_code', 'code');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_id', 'student_id')->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }
}
