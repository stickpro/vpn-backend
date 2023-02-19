<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        Plan::create(['name' => 'Start', 'device_count' => 1, 'price'=> 200]);
        Plan::create(['name' => 'Pro', 'device_count' => 3, 'price'=> 500]);
        Plan::create(['name' => 'Ultima', 'device_count' => 5, 'price'=> 700]);
    }
}
