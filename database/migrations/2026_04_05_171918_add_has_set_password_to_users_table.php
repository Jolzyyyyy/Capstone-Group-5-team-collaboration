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
        Schema::table('users', function (Blueprint $table) {
            // Ito ang magsasabi kung nakapag-set na ng manual password ang Google user
            // Default ay 'false' dahil sa Google Register, random password lang ang sineset natin
            $table->boolean('has_set_password')->default(false)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tanggalin ang column kapag ni-rollback ang migration
            $table->dropColumn('has_set_password');
        });
    }
};