<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private readonly PendingRequest $client)
    {
        $this->client->baseUrl(config('services.flash_call.domain'));
    }

    /**
     * @param string $phone
     * @param int $code
     * @return User
     */
    public function auth(string $phone, int $code): User
    {
        $user = User::where('phone', '=', $phone)->firstOrFail();
        if ($user->auth_code !== $code) {
            throw ValidationException::withMessages("Невалидный код");
        }
        $user->update(['auth_code' => null]);

        return $user;
    }

    /**
     * @param string $phone
     * @return bool
     */
    public function sendCode(string $phone): bool
    {
        $user = $this->createOrFirstUser($phone);

        $response = $this->client
            ->withUrlParameters([
                'endpoint' => 'voice',
                'apiKey' => config('services.flash_call.apiKey'),
                'phone' => $user->phone
            ])
            ->get('{+endpoint}/{apiKey}/{phone}/');

        if ($response->successful()) {
            return $user->update(['auth_code' => intval($response->collect('code')->first())]);
        }

        return false;
    }

    private function createOrFirstUser(string $phone): User
    {
        return User::firstOrCreate(
            ['phone' => $phone],
        );
    }
}
