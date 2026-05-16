<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser; // <--- 1. Import this
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Relations\HasMany;

// #[Fillable(['name', 'email', 'password'])]
// #[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements LaratrustUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */


    protected $fillable = [
        'name',
        'nama_penuh',
        'email',
        'password',
        'role',
        'status',
        'rejected_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function results()
    {
        return $this->hasMany(StudentResult::class, 'user_id');
    }


    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_tutor', 'user_id', 'subject_id');
    }

    // Link to the Tutor Profile (for scoring and methodology)

    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class, 'user_id');
    }


     //Link to the Student Profile (for demographics and learning style)

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    public function enrollments(): HasMany
    {
        // 'student_id' is the foreign key in your enrollments table
        return $this->hasMany(Enrollment::class, 'student_id');
    }

}
