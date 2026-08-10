<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->enum('meal_type', [
                'breakfast',
                'lunch',
                'supper',
            ])->after('name');

            $table->unsignedTinyInteger('day_of_week')
                ->after('meal_type')
                ->comment('1 = Monday, 7 = Sunday');

            $table->index([
                'meal_plan_id',
                'day_of_week',
                'meal_type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropIndex([
                'meals_meal_plan_id_day_of_week_meal_type_index',
            ]);

            $table->dropColumn([
                'meal_type',
                'day_of_week',
            ]);
        });
    }
};