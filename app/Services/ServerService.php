<?php

namespace App\Services;

use App\Models\Server;
use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;

class ServerService
{
    public function __construct(
            private PendingRequest $client,
            private Server         $server
    ) {
        $this->client->baseUrl($this->server->ip);
        $this->client->withToken($this->server->auth_token);
    }

    public function createNewPeer(User $user): \Illuminate\Support\Collection
    {
        $data = $this->client->post('/api/v1/peers/', [
                'name'  => $user->name,
                'email' => $user->email
        ])->collect();



        return $data;
    }
}