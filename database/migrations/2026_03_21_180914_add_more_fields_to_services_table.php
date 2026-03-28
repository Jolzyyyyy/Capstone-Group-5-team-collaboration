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
        Schema::table('services', function (Blueprint $table) {
            $table->string('service_item_id')->nullable()->after('id');
            $table->json('paper_sizes')->nullable()->after('description');
            $table->json('color_modes')->nullable()->after('paper_sizes');
            $table->json('printing_categories')->nullable()->after('color_modes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'service_item_id',
                'paper_sizes',
                'color_modes',
                'printing_categories',
            ]);
        });
    }
};