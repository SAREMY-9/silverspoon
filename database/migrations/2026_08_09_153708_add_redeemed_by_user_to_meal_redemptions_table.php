<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meal_redemptions', function (Blueprint $table) {
            $table->foreignId('redeemed_by_user_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('meal_redemptions', function (Blueprint $table) {
            $table->dropForeign([
                'redeemed_by_user_id',
            ]);

            $table->dropColumn(
                'redeemed_by_user_id'
            );
        });
    }
};