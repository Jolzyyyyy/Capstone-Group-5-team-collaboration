<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'admin_client_id')) {
                $table->foreignId('admin_client_id')
                    ->nullable()
                    ->after('role')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'admin_client_id')) {
                $table->foreignId('admin_client_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'admin_client_id')) {
                $table->dropForeign(['admin_client_id']);
                $table->dropColumn('admin_client_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'admin_client_id')) {
                $table->dropForeign(['admin_client_id']);
                $table->dropColumn('admin_client_id');
            }
        });
    }
};
