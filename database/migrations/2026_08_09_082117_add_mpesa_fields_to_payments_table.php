<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('checkout_request_id')
                ->nullable()
                ->unique()
                ->after('transaction_reference');

            $table->string('merchant_request_id')
                ->nullable()
                ->index()
                ->after('checkout_request_id');

            $table->string('phone')
                ->nullable()
                ->after('merchant_request_id');

            $table->text('provider_response')
                ->nullable()
                ->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'checkout_request_id',
                'merchant_request_id',
                'phone',
                'provider_response',
            ]);
        });
    }
};