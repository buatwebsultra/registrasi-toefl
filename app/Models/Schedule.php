<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'room',
        'capacity',
        'used_capacity',
        'status',
        'category',
        'signature_name',
        'signature_nip',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function isFull()
    {
        return $this->used_capacity >= $this->capacity;
    }

    public function isAvailable()
    {
        // Must be available status, not full, and test date must be at least 2 days in the future
        return $this->status === 'available' && 
               !$this->isFull() && 
               $this->date->gt(now()->addDays(1)->endOfDay());
    }

    public function scopeAvailable($query)
    {
        // Only show schedules where test date is at least 2 days away (registration closes 48h before)
        return $query->where('status', 'available')
            ->whereRaw('used_capacity < capacity')
            ->whereDate('date', '>', now()->addDays(1)->toDateString());
    }
}