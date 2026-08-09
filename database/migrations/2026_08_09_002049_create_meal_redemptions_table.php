<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_redemptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meal_entitlement_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('meal_id')
                ->constrained()
                ->restrictOnDelete();

            $table->dateTime('redeemed_at');
            $table->string('reference')->unique();

            $table->timestamps();

            $table->index(['user_id', 'redeemed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_redemptions');
    }
};