<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get Subscriptions relatinship.
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(PlanUser::class, 'user_id');
    }

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

    /**
     * Check if the mode has a due, unpaid subscription.
     *
     * @return bool
     */
    public function hasDueSubscription(): bool
    {
        return (bool) $this->lastDueSubscription();
    }

    public function lastUnpaidSubscription()
    {
        return $this->subscriptions()->latest('start_at')->notCancelled()->unpaid()->first();
    }

    /**
     * When a subscription is due, it means it was created, but not paid.
     * For example, on subscription, if your user wants to subscribe to another subscription and has a due (unpaid) one, it will
     * check for the last due, will cancel it, and will re-subscribe to it.
     *
     * @return null|PlanUser Null or a Plan Subscription instance.
     */
    public function lastDueSubscription(): PlanUser|null
    {
        if (! $this->hasSubscriptions()) {
            return;
        }

        if ($this->hasActiveSubscription()) {
            return;
        }

        $lastActiveSubscription = $this->lastActiveSubscription();

        if (! $lastActiveSubscription) {
            return $this->lastUnpaidSubscription();
        }

        $lastSubscription = $this->lastSubscription();

        if ($lastActiveSubscription->is($lastSubscription)) {
            return;
        }

        return $this->lastUnpaidSubscription();
    }

    public function subscribeTo(Plan $plan, int $duration = 30,  bool $isRecurring = true)
    {

        if ($duration < 1 || $this->hasActiveSubscription()) {
            return false;
        }

        if ($this->hasDueSubscription()) {
            $this->lastDueSubscription()->delete();
        }
    }
}
