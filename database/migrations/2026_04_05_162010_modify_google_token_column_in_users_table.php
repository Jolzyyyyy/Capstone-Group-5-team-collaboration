<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ginagawa nating 'text' ang type para kasya kahit gaano kahaba ang token
            $table->text('google_token')->nullable()->change();
            $table->text('google_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_token', 255)->nullable()->change();
            $table->string('google_id', 255)->nullable()->change();
        });
    }
};