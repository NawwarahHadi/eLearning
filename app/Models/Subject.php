<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Subject extends Model
{
    //
    use HasFactory;

    protected $table ='subjects';

    protected $fillable = ['name', 'slug'];


    public function users()
    {
        return $this->hasMany(User::class, 'subject_expert_id');
    }

    public function results()
    {
        return $this->hasMany(StudentResult::class, 'subject_id');
    }

}
