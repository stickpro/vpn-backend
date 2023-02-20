<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserConfig extends Model
{
    protected $fillable = [
            'user_id',
            'server_id',
            'peer_id',
            'data',
            'peer_data'
    ];

    /**
     * @return BelongsTo
     */
    public function server(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}