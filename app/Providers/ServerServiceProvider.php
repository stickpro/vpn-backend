<?php

namespace App\Providers;

use App\Models\Server;
use App\Services\ServerService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\ServiceProvider;

class ServerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // TODO change interface
        $this->app->bind(ServerService::class, function ($app) {
            $client = $app->make(PendingRequest::class);
            $server = $app->make(Server::class);

            return new ServerService($client, $server);
        });
    }
}