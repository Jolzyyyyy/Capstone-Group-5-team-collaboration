<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            // You can add more seeders here later:
            // UserSeeder::class,
            // OrderSeeder::class,
        ]);
    }
}
