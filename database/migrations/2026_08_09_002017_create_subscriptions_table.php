<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('meal_plan_id')
                ->constrained('meal_plans')
                ->restrictOnDelete();

            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            $table->enum('status', [
                'pending',
                'active',
                'expired',
                'cancelled'
            ])->default('pending');

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};