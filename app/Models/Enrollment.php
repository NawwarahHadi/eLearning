<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Enrollment extends Model
{
    // Nama table yang kita buat dalam migration tadi
    protected $table = 'enrollments';

    // Ruangan yang dibenarkan untuk disimpan (Mass Assignment)
    protected $fillable = [
        'student_id',
        'class_id',
        'tutor_id',
        'schedule_id',
        'status'
    ];

    // --- HUBUNGAN (RELATIONSHIPS) ---

    // Hubungan ke Pelajar (User)
    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Hubungan ke Tutor (User)
    public function tutor() {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    // Hubungan ke Kelas
    public function class() {
        return $this->belongsTo(CreateClass::class, 'class_id');
    }

    // Hubungan ke Slot Jadual Spesifik
    public function schedule() {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }


}
