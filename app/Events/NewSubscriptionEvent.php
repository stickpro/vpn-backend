<?php

namespace App\Events;

use App\Models\PlansUser;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class NewSubscriptionEvent
{
    use SerializesModels;

    public User $model;
    public PlansUser $subscription;

    /**
     * @param  User  $model The model that subscribed.
     * @param  PlansUser  $subscription Subscription the model has subscribed to.
     * @return void
     */
    public function __construct(User $model, PlansUser $subscription)
    {
        $this->model = $model;
        $this->subscription = $subscription;
    }
}