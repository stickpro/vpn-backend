<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function __construct(private readonly PendingRequest $client)
    {
        $this->client->baseUrl(config('services.flash_call.domain'));
    }

    public function auth(int $phone): User
    {
        $user = User::firstOrCreate(
            ['phone' => $phone],
        );
        $this->sendCode($user->phone);
        return $user;
    }

    private function sendCode(string $phone): bool
    {
        $response = $this->client
            ->withUrlParameters([
                'endpoint' => 'voice',
                'apiKey' => config('services.flash_call.apiKey'),
                'phone' => $phone
            ])
            ->get('{+endpoint}/{apiKey}/{phone}/');

        Log::info($response);

        if ($response->successful()) {
            Log::info($response->collect());
            return $response->collect();
        }

        return false;
    }
}
