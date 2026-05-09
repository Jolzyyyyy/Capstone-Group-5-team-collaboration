<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'invite_token')) {
                $table->string('invite_token')->nullable()->unique()->after('approved_by');
            }

            if (!Schema::hasColumn('users', 'invite_expires_at')) {
                $table->timestamp('invite_expires_at')->nullable()->after('invite_token');
            }

            if (!Schema::hasColumn('users', 'invitation_accepted_at')) {
                $table->timestamp('invitation_accepted_at')->nullable()->after('invite_expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $dropColumns = [];

            foreach (['invite_token', 'invite_expires_at', 'invitation_accepted_at'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if ($dropColumns !== []) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
