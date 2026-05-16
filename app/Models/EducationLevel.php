<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class EducationLevel extends Model
{
    protected $table = 'education_level';

    protected $fillable = [
        'name',
        'weight'
    ];

    protected $guarded = [
        'id',
    ];
}
