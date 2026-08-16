<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_meal_selections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subscription_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('meal_id')
                ->constrained()
                ->restrictOnDelete();

            $table->unsignedTinyInteger('day_of_week')
                ->comment('1 = Monday, 7 = Sunday');

            $table->enum('meal_type', [
                'breakfast',
                'lunch',
                'supper',
            ]);

            /*
             * Price snapshot.
             *
             * If the admin changes the meal price later,
             * an existing subscription keeps the price it
             * was purchased at.
             */
            $table->decimal('unit_price', 10, 2);

            $table->timestamps();

            $table->unique(
                [
                    'subscription_id',
                    'day_of_week',
                    'meal_type',
                ],
                'sub_meal_sel_unique'
            );

            
            $table->index([
                'subscription_id',
                'meal_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_meal_selections');
    }
};