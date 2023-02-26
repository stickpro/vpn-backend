<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserConfig;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class UserConfigPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, UserConfig $config): bool
    {
        return $user->id === $config->user_id;
    }

    public function create(User $user): bool
    {
        return $user->activePlanInfo()->device_count > $user->userConfigs()->count();
    }

    public function update(User $user, UserConfig $userConfig): bool
    {
    }

    public function delete(User $user, UserConfig $config): bool
    {
        return $user->id === $config->user_id;
    }

    public function restore(User $user, UserConfig $userConfig): bool
    {
    }

    public function forceDelete(User $user, UserConfig $config): bool
    {
        return $user->id === $config->user_id;
    }
}