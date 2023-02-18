<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;

class ServerService
{
    public function __construct(
            private PendingRequest $client,
            private Server $server
    ){
      $this->client->baseUrl($this->server->ip);
      $this->client->withToken($this->server->auth_token);
    }

    public function createNewPeer()
    {
        $data = $this->client->post('/api/peers');
        return $data->body();
    }
}