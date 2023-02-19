<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\ServerService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserConfigController extends Controller
{
    public function store(Request $request)
    {
        $server = Server::where('id', $request->input('id'))->first();

        $serverService = new ServerService(app(PendingRequest::class), $server);
        $config = $serverService->createNewPeer($request->user());
        return JsonResource::make($config);
    }
}