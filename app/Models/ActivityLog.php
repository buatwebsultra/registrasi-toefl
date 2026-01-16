<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'user_name',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user()
    {
        if ($this->user_type === 'admin') {
            return $this->belongsTo(User::class, 'user_id');
        } elseif ($this->user_type === 'participant') {
            return $this->belongsTo(Participant::class, 'user_id');
        }
        
        return null;
    }
}
