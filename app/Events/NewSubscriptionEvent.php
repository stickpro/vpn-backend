<?php

namespace App\Events;

use App\Models\UserPlan;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class NewSubscriptionEvent
{
    use SerializesModels;

    public User $user;
    public UserPlan $subscription;

    /**
     * @param  User  $model The model that subscribed.
     * @param  UserPlan  $subscription Subscription the model has subscribed to.
     * @return void
     */
    public function __construct(User $user, UserPlan $subscription)
    {
        $this->user = $user;
        $this->subscription = $subscription;
    }
}