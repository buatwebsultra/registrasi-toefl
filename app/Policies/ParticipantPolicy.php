<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Participant;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParticipantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Participant $participant): bool
    {
        // Admin can view any participant
        if ($user->isAdmin()) {
            return true;
        }
        
        // Participant can only view their own record
        return $user->id === $participant->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Participant $participant): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Participant $participant): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Participant $participant): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Participant $participant): bool
    {
        return $user->isAdmin();
    }
    
    /**
     * Determine whether the user can confirm participant registration
     */
    public function confirm(User $user, Participant $participant): bool
    {
        return $user->isAdmin();
    }
    
    /**
     * Determine whether the user can reject participant registration
     */
    public function reject(User $user, Participant $participant): bool
    {
        return $user->isAdmin();
    }
    
    /**
     * Determine whether the user can update test score
     */
    public function updateTestScore(User $user, Participant $participant): bool
    {
        return $user->isAdmin();
    }
}