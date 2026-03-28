<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'service_variation_id')) {
                $table->foreignId('service_variation_id')
                    ->nullable()
                    ->after('service_id')
                    ->constrained('service_variations')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('order_items', 'service_item_id')) {
                $table->string('service_item_id')->nullable()->after('service_variation_id');
            }

            if (!Schema::hasColumn('order_items', 'variation_label')) {
                $table->string('variation_label')->nullable()->after('service_name');
            }

            // Do not add price_type here because it already exists.
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'service_variation_id')) {
                $table->dropForeign(['service_variation_id']);
                $table->dropColumn('service_variation_id');
            }

            if (Schema::hasColumn('order_items', 'service_item_id')) {
                $table->dropColumn('service_item_id');
            }

            if (Schema::hasColumn('order_items', 'variation_label')) {
                $table->dropColumn('variation_label');
            }
        });
    }
};