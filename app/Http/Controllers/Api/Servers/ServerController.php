<?php

namespace App\Http\Controllers\Api\Servers;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::orderBy('id')->simplePaginate();
        return JsonResource::make($servers);
    }
}