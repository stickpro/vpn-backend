<?php

namespace App\Http\Controllers\Api\Plans;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('id')->simplePaginate();
        return JsonResource::make($plans);
    }

    public function store()
    {

    }

    public function show(Plan $plan)
    {
        return JsonResource::make($plan);
    }


}