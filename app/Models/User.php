<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_OPERATOR = 'operator';
    const ROLE_PRODI = 'prodi';
    const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'study_program_id',
        'nip',
        'jabatan',
        'photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is a super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    /**
     * Check if the user is an admin (or super admin)
     */
    public function isAdmin()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN]);
    }

    /**
     * Check if the user is an operator (or admin, or super admin)
     */
    public function isOperator()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_OPERATOR, self::ROLE_PRODI]);
    }

    /**
     * Check if the user is a prodi admin
     */
    public function isProdi()
    {
        return $this->role === self::ROLE_PRODI;
    }

    /**
     * Relationship with StudyProgram
     */
    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
    /**
     * Get photo URL for secure retrieval
     */
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo_path) {
            return null;
        }

        if (\Storage::disk('private')->exists($this->photo_path)) {
            return route('admin.profile.photo');
        }

        return null;
    }
}
