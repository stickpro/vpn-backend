<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasPlans;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPlans;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
            'name',
            'email',
            'password',
            'phone',
            'auth_code'
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
        return $this->hasMany(UserPlan::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function userConfigs(): HasMany
    {
        return $this->hasMany(UserConfig::class, 'user_id');
    }

    /**
     * @param  string  $data
     * @param  string  $qrcode
     * @return mixed
     */
    public function createUserConfig(object $data, Server $server): UserConfig|false
    {
        return $this->userConfigs()->save(new UserConfig([
                'peer_id'   => $data['Peer']['id'],
                'server_id' => $server->id,
                'data'      => json_encode($data['Peer']),
                'peer_data' => $data['PeerConfig']
        ]));
    }

}
