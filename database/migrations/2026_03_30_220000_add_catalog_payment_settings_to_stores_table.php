<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table): void {
            $table->json('accepted_payment_methods')->nullable()->after('wholesale_min_quantity');
            $table->json('accepted_card_brands')->nullable()->after('accepted_payment_methods');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table): void {
            $table->dropColumn(['accepted_payment_methods', 'accepted_card_brands']);
        });
    }
};
