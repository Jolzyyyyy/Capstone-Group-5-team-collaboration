<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_variations', function (Blueprint $table) {
            if (!Schema::hasColumn('service_variations', 'variation_image_path')) {
                $table->string('variation_image_path')->nullable()->after('service_item_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_variations', function (Blueprint $table) {
            if (Schema::hasColumn('service_variations', 'variation_image_path')) {
                $table->dropColumn('variation_image_path');
            }
        });
    }
};
