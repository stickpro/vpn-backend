<?php

namespace App\Listeners;

use App\Events\NewSubscriptionEvent;

class NewSubscriptionListener
{
    public function __construct()
    {
    }

    public function handle(NewSubscriptionEvent $event): void
    {
        $event->user->decrement('balance', $event->subscription->charging_price);
    }
}