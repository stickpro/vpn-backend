<?php

namespace App\Http\Controllers\Api\Plans;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\PlanUserStoreRequest;
use App\Models\Plan;
use App\Models\Server;
use App\Models\User;
use App\Services\ServerService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PlanUserController extends Controller
{

    public function index(Request $request)
    {
        return JsonResource::make($request->user()->activeSubscription());
    }
    public function store(PlanUserStoreRequest $request)
    {
        $plan = Plan::where('id', $request->input('plan_id'))->firstOrFail();
        $user = $request->user();

        if($this->user->balance < $plan->price) {
            throw new HttpException(402, 'Insufficient funds');
        }

        return JsonResource::make($user->subscribeTo($plan));
    }
}