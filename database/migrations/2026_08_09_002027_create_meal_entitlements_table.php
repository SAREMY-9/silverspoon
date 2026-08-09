<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_entitlements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subscription_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('meal_id')
                ->constrained()
                ->restrictOnDelete();

            $table->enum('status', [
                'available',
                'redeemed',
                'expired'
            ])->default('available');

            $table->dateTime('expires_at')->nullable();

            $table->timestamps();

            $table->index(['subscription_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_entitlements');
    }
};