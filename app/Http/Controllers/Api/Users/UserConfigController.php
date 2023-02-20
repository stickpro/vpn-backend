<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\UserConfig;
use App\Services\ServerService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UserConfigController extends Controller
{
    public function index(Request $request)
    {
        return JsonResource::make($request->user()->userConfigs()->get());
    }

    public function store(Request $request)
    {
        $server = Server::where('id', $request->input('id'))->first();
        $serverService = new ServerService(app(PendingRequest::class), $server);

        $config = $serverService->createNewPeer($request->user());
        return JsonResource::make($config);
    }

    public function destroy(UserConfig $config)
    {
        $server = Server::where('id', $config->server_id)->first();
        $serverService = new ServerService(app(PendingRequest::class), $server);

        if (!$serverService->deletePeer($config)) {
            return (new JsonResource(['message' => 'Error deleting']))
                    ->response()
                    ->setStatusCode(400);
        }

        return JsonResource::make(['message' => 'Delete Success']);
    }
}