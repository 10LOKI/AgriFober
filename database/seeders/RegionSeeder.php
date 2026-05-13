<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            ['nom' => 'Dakar', 'code' => 'DK', 'pays' => 'Sénégal'],
            ['nom' => 'Thiès', 'code' => 'TH', 'pays' => 'Sénégal'],
            ['nom' => 'Saint-Louis', 'code' => 'SL', 'pays' => 'Sénégal'],
            ['nom' => 'Kaolack', 'code' => 'KL', 'pays' => 'Sénégal'],
            ['nom' => 'Ziguinchor', 'code' => 'ZG', 'pays' => 'Sénégal'],
            ['nom' => 'Diourbel', 'code' => 'DB', 'pays' => 'Sénégal'],
            ['nom' => 'Kolda', 'code' => 'KD', 'pays' => 'Sénégal'],
            ['nom' => 'Tambacounda', 'code' => 'TC', 'pays' => 'Sénégal'],
            ['nom' => 'Kaffrine', 'code' => 'KF', 'pays' => 'Sénégal'],
            ['nom' => 'Kédougou', 'code' => 'KG', 'pays' => 'Sénégal'],
        ];

        foreach ($regions as $region) {
            Region::firstOrCreate(
                ['code' => $region['code']],
                $region
            );
        }
    }
}
