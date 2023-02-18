<?php

namespace App\Traits;

use App\Events\NewSubscriptionEvent;
use App\Events\UpgradeSubscriptionEvent;
use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

trait HasPlans
{
    /**
     * Return the current subscription relatinship.
     * @return HasMany
     */
    public function currentSubscription(): HasMany
    {
        return $this->subscriptions()
                ->where('start_at', '<', now())
                ->where('expiration_at', '>', now());
    }


    /**
     * Return the current active subscription.
     * @return mixed
     */
    public function activeSubscription(): mixed
    {
        return $this->currentSubscription()->paid()->notCancelled()->first();
    }

    public function lastSubscription()
    {
        if (! $this->hasSubscriptions()) {
            return null;
        }

        if ($this->hasActiveSubscription()) {
            return $this->activeSubscription();
        }

        return $this->subscriptions()->latest('start_at')->first();
    }

    /**
     * @return UserPlan|mixed|null
     */
    public function lastActiveSubscription(): mixed
    {
        if (! $this->hasSubscriptions()) {
            return null;
        }

        if ($this->hasActiveSubscription()) {
            return $this->activeSubscription();
        }

        return $this->subscriptions()->latest('start_at')->paid()->notCancelled()->first();
    }

    /**
     * Check if the user has subscriptions.
     * @return bool
     */
    public function hasSubscriptions(): bool
    {
        return (bool) ($this->subscriptions()->count() > 0);
    }

    /**
     * Check if the model has an active subscription right now.
     *
     * @return bool
     */
    public function hasActiveSubscription(): bool
    {
        return (bool) $this->activeSubscription();
    }

    public function lastUnpaidSubscription()
    {
        return $this->subscriptions()->latest('start_at')->notCancelled()->unpaid()->first();
    }


    /**
     * @param  Plan  $plan
     * @param  int  $duration
     * @param  bool  $isRecurring
     * @return false|Model
     */
    public function subscribeTo(Plan $plan, int $duration = 30,  bool $isRecurring = true): Model|false
    {
        if ($duration < 1 || $this->hasActiveSubscription()) {
            throw ValidationException::withMessages([__('You have active plan')]);
        }

        $subscription = $this->subscriptions()->save(new UserPlan([
                'plan_id' => $plan->id,
                'start_at' => now()->subSeconds(1),
                'expiration_at' => now()->addDays($duration),
                'cancelled_on' => null,
                'is_paid' => true,
                'charging_price' => $plan->price,
                'is_recurring' => $isRecurring,
                'recurring_each_days' => $duration,
        ]));
        event(new NewSubscriptionEvent($this, $subscription));

        return $subscription;
    }

    public function upgradeCurrentPlanTo($newPlan, int $duration = 30, bool $startFromNow = true, bool $isRecurring = true)
    {
        if (! $this->hasActiveSubscription()) {
            return $this->subscribeTo($newPlan, $duration, $isRecurring);
        }

        if ($duration < 1) {
            return false;
        }

        $activeSubscription = $this->activeSubscription();
        $activeSubscription->load(['plan']);

        $subscription = $this->extendCurrentSubscriptionWith($duration, $startFromNow, $isRecurring);
        $oldPlan = $activeSubscription->plan;

        if ($subscription->plan_id != $newPlan->id) {
            $subscription->update([
                    'plan_id' => $newPlan->id,
            ]);
        }

        event(new UpgradeSubscriptionEvent($this, $subscription, $startFromNow, $oldPlan, $newPlan));

        return $subscription;
    }

    public function extendCurrentSubscriptionWith(int $duration = 30, bool $startFromNow = true, bool $isRecurring = true)
    {
        if (! $this->hasActiveSubscription()) {
            if ($this->hasSubscriptions()) {
                $lastActiveSubscription = $this->lastActiveSubscription();
                $lastActiveSubscription->load(['plan']);

                return $this->subscribeTo($lastActiveSubscription->plan, $duration, $isRecurring);
            }

            return $this->subscribeTo(Plan::first(), $duration, $isRecurring);
        }

        if ($duration < 1) {
            return false;
        }

        $activeSubscription = $this->activeSubscription();

        if ($startFromNow) {
            $activeSubscription->update([
                    'expiration_at' => now()->parse($activeSubscription->expires_on)->addDays($duration),
            ]);

            return $activeSubscription;
        }

        return $this->subscriptions()->save(new UserPlan([
                'plan_id' => $activeSubscription->plan_id,
                'start_at' => now()->parse($activeSubscription->expires_on),
                'expiration_at' => now()->parse($activeSubscription->expires_on)->addDays($duration),
                'cancelled_on' => null,
                'is_recurring' => $isRecurring,
                'recurring_each_days' => $duration,
        ]));
    }
}