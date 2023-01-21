<?php

namespace App\Observers;

use App\Enums\Role;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        $user->assignRole(Role::USER->value);
    }

}