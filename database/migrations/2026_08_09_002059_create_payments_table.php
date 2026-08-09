<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('KES');

            $table->string('provider')->nullable();
            $table->string('transaction_reference')->unique();

            $table->enum('status', [
                'pending',
                'successful',
                'failed',
                'refunded'
            ])->default('pending');

            $table->dateTime('paid_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};