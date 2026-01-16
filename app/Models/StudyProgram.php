<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'passing_grade',
        'faculty_id',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}