<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    use HandlesAuthorization;

    public function isAdmin(User $user)
    {
        if ($user->role->id === 1) {
            return true;
        }

        return false;
    }
}
