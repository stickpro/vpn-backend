<?php

namespace App\Services;

use App\Models\Server;
use App\Models\User;
use App\Models\UserConfig;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;

class ServerService
{
    public function __construct(
            private readonly PendingRequest $client,
            private readonly Server         $server
    ) {
        $this->client->baseUrl($this->server->ip);
        $this->client->withToken($this->server->auth_token);
    }

    public function createNewPeer(User $user): \Illuminate\Support\Collection
    {
        $response = $this->client->post('/api/v1/peers/', [
                'name'           => $user->name,
                'email'          => $user->email,
                'allowed_ips'    => ["0.0.0.0/0"],
                'use_server_dns' => true
        ]);

        // Check if the response was successful (status code 200-299)
        if ($response->successful()) {
            $data = $response->collect();
            $user->createUserConfig($data, $this->server);
            return $data;
        } else {
            Log::error('Failed to create new peer: '.$response->body());
        }
    }

    /**
     * @param  UserConfig  $userConfig
     * @return bool
     */
    public function deletePeer(UserConfig $userConfig): bool
    {
        $response = $this->client->delete('/api/v1/peers/'.$userConfig->peer_id);

        if ($response->successful()) {
            $userConfig->delete();
            return true;
        } else {
            Log::error('Failed to delete peer: '.$response->body());
            return false;
        }
    }


}