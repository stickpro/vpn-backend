<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlansPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        
    }

    public function view(User $user, Plan $plans)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user, Plan $plans)
    {
    }

    public function delete(User $user, Plan $plans)
    {
    }

    public function restore(User $user, Plan $plans)
    {
    }

    public function forceDelete(User $user, Plan $plans)
    {
    }
}