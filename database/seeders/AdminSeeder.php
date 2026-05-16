<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'email' => 'superadmin@agrifober.com',
                'username' => 'superadmin',
                'name' => 'Super Admin',
                'password' => Hash::make('Super@1234'),
                'role' => 'admin',
                'is_approved' => true,
                'region' => 'Dakar',
            ],
            [
                'email' => 'tech@test.com',
                'username' => 'jtech',
                'name' => 'Jean Technicien',
                'password' => Hash::make('Tech@1234'),
                'role' => 'technicien',
                'is_approved' => true,
                'region' => 'Thiès',
            ],
            [
                'email' => 'farmer@test.com',
                'username' => 'pdupont',
                'name' => 'Pierre Dupont',
                'password' => Hash::make('Farmer@1234'),
                'role' => 'agriculteur',
                'is_approved' => true,
                'region' => 'Dakar',
                'experience_level' => 'expert',
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                $account
            );
        }
    }
}
