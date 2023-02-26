<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Plan;
use App\Models\UserConfig;
use App\Policies\PlansPolicy;
use App\Policies\UserConfigPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
            Plan::class       => PlansPolicy::class,
            UserConfig::class => UserConfigPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
