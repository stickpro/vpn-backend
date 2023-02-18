<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use SoftDeletes;
    protected $fillable = [
            'ip',
            'auth_token',
            'name',
            'max_users'
    ];
}