<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ClassSchedule extends Model
{
      use HasFactory;


    protected $table = 'class_schedules';

    protected $fillable = [
        'class_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function parentClass()
    {
        return $this->belongsTo(CreateClass::class, 'class_id');
    }
}
