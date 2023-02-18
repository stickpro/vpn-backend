<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained();
            $table->foreignId('user_id')->constrained();

            $table->decimal('charging_price', 8, 2)->nullable();

            $table->boolean('is_paid')->default(false);
            $table->boolean('is_recurring')->default(true);

            $table->integer('recurring_each_days')->default(30);

            $table->dateTime('start_at')->nullable();
            $table->dateTime('expiration_at')->nullable();
            $table->timestamp('cancelled_on')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans_users');
    }
};