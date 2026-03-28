<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_variations', function (Blueprint $table) {
            $table->id();

            // 🔗 link to services table
            $table->foreignId('service_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🆔 your generated ID
            $table->string('service_item_id')->unique();

            // 🎯 variation fields
            $table->string('printing_category')->nullable();
            $table->string('color_mode')->nullable();
            $table->string('product_size')->nullable();
            $table->string('finish_type')->nullable();
            $table->string('package_type')->nullable();

            // 💰 pricing
            $table->decimal('retail_price', 10, 2)->default(0);
            $table->decimal('bulk_price', 10, 2)->default(0);

            // ✅ active toggle
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_variations');
    }
};