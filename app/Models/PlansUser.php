<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class PlansUser extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $casts = [
            'is_paid'      => 'boolean',
            'is_recurring' => 'boolean',
    ];

    protected $dates = [
            'start_at',
            'expiration_at',
            'cancelled_on'
    ];

    /**
     * @return BelongsTo
     */
    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * @return HasMany
     */
    public function usages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'user_id');
    }
    /* scope start */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }
    public function scopeExpired($query)
    {
        return $query->where('expiration_at', '<', now()->toDateTime());
    }
    public function scopeCancelled($query)
    {
        return $query->whereNotNull('cancelled_on');
    }
    public function scopeNotCancelled($query)
    {
        return $query->whereNull('cancelled_on');
    }

    /* scope end */

    /**
     * Checks if the current subscription has started.
     * @return bool
     */
    public function hasStarted(): bool
    {
        return (bool) now()->greaterThanOrEqualTo(Carbon::parse($this->starts_on));
    }


    /**
     * Checks if the current subscription has expired.
     *
     * @return bool
     */
    public function hasExpired(): bool
    {
        return (bool) now()->greaterThan(Carbon::parse($this->expires_on));
    }

    /**
     * Checks if the current subscription is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) ($this->hasStarted() && ! $this->hasExpired());
    }

    /**
     * Get the remaining days in this subscription.
     *
     * @return int
     */
    public function remainingDays(): int
    {
        if ($this->hasExpired()) {
            return (int) 0;
        }
        return (int) now()->diffInDays(Carbon::parse($this->expires_on));
    }


    /**
     * Checks if the current subscription is cancelled (expiration date is in the past & the subscription is cancelled).
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return (bool) $this->cancelled_on != null;
    }

    /**
     * Checks if the current subscription is pending cancellation.
     *
     * @return bool
     */
    public function isPendingCancellation(): bool
    {
        return (bool) ($this->isCancelled() && $this->isActive());
    }

    /**
     * Cancel this subscription.
     *
     * @return self $this
     */
    public function cancel(): static
    {
        $this->update([
                'cancelled_on' => Carbon::now(),
        ]);

        return $this;
    }
}