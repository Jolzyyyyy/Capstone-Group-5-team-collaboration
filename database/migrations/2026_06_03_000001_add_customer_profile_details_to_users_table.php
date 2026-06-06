<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'phone' => fn () => $table->string('phone')->nullable()->after('email'),
                'birthdate' => fn () => $table->string('birthdate')->nullable()->after('phone'),
                'gender' => fn () => $table->string('gender')->nullable()->after('birthdate'),
                'street' => fn () => $table->string('street')->nullable()->after('gender'),
                'barangay' => fn () => $table->string('barangay')->nullable()->after('street'),
                'region' => fn () => $table->string('region')->nullable()->after('barangay'),
                'city' => fn () => $table->string('city')->nullable()->after('region'),
                'postal_code' => fn () => $table->string('postal_code')->nullable()->after('city'),
                'company' => fn () => $table->string('company')->nullable()->after('postal_code'),
                'profile_photo' => fn () => $table->longText('profile_photo')->nullable()->after('company'),
                'preferences' => fn () => $table->json('preferences')->nullable()->after('profile_photo'),
            ] as $column => $definition) {
                if (! Schema::hasColumn('users', $column)) {
                    $definition();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'phone',
                'birthdate',
                'gender',
                'street',
                'barangay',
                'region',
                'city',
                'postal_code',
                'company',
                'profile_photo',
                'preferences',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
