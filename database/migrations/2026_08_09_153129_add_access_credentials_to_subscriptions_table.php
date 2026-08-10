<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('access_code', 32)
                ->nullable()
                ->unique()
                ->after('status');

            $table->string('qr_token', 64)
                ->nullable()
                ->unique()
                ->after('access_code');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropUnique('subscriptions_access_code_unique');
            $table->dropUnique('subscriptions_qr_token_unique');

            $table->dropColumn([
                'access_code',
                'qr_token',
            ]);
        });
    }
};