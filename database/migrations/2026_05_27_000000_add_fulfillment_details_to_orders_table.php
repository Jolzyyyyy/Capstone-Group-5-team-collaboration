<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone', 40)->nullable()->after('customer_email');
            }

            if (!Schema::hasColumn('orders', 'fulfillment_method')) {
                $table->string('fulfillment_method', 30)->default('pickup')->after('customer_phone');
            }

            if (!Schema::hasColumn('orders', 'delivery_address')) {
                $table->text('delivery_address')->nullable()->after('fulfillment_method');
            }

            if (!Schema::hasColumn('orders', 'customer_note')) {
                $table->text('customer_note')->nullable()->after('delivery_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['customer_note', 'delivery_address', 'fulfillment_method', 'customer_phone'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
