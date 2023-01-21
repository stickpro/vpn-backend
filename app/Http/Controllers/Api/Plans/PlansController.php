<?php

namespace App\Http\Controllers\Api\Plans;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Resources\Json\JsonResource;

class PlansController extends Controller
{
    public function index()
    {
        $plans = Plan::paginate();
        return JsonResource::make($plans);
    }

    public function show(Plan $plan)
    {
        return JsonResource::make($plan);
    }


}