<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test accounts via AdminSeeder (ensures consistent passwords/roles)
        $this->call([
            AdminSeeder::class,
        ]);

        // Seed reference data
        $this->call([
            RegionSeeder::class,
            CultureSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
