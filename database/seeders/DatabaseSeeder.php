<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@agriforb.com'],
            [
                'username' => 'admin',
                'name' => 'Admin Agriforb',
                'password' => bcrypt('Admin@2024'),
                'role' => 'admin',
                'is_approved' => true,
            ]
        );

        // Create test farmer
        User::firstOrCreate(
            ['email' => 'farmer@example.com'],
            [
                'username' => 'testfarmer',
                'name' => 'Test Farmer',
                'password' => bcrypt('Farmer@2024'),
                'role' => 'agriculteur',
                'is_approved' => true,
                'region' => 'Dakar',
                'experience_level' => 'intermediaire',
            ]
        );

        // Seed reference data
        $this->call([
            RegionSeeder::class,
            CultureSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
