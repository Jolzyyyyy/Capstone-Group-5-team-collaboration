<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Link to orders
            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Link to services (what customer ordered)
            $table->foreignId('service_id')
                  ->constrained()
                  ->restrictOnDelete();

            // Snapshot fields (important even if service changes later)
            $table->string('service_name');
            $table->enum('price_type', ['retail', 'bulk'])->default('retail');

            // Pricing + quantity
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->unsignedInteger('quantity')->default(1);

            // unit_price * quantity
            $table->decimal('subtotal', 10, 2)->default(0);

            $table->timestamps();

            // optional indexes (helpful for admin reporting)
            $table->index(['order_id']);
            $table->index(['service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
