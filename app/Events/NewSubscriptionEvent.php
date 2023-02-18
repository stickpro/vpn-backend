<?php

namespace App\Events;

use App\Models\UserPlan;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class NewSubscriptionEvent
{
    use SerializesModels;

    public User $model;
    public UserPlan $subscription;

    /**
     * @param  User  $model The model that subscribed.
     * @param  UserPlan  $subscription Subscription the model has subscribed to.
     * @return void
     */
    public function __construct(User $model, UserPlan $subscription)
    {
        $this->model = $model;
        $this->subscription = $subscription;
    }
}