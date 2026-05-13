<?php

namespace Database\Seeders;

use App\Models\Culture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CultureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cultures = [
            [
                'nom_commun' => 'Tomate',
                'nom_scientifique' => 'Solanum lycopersicum',
                'type' => 'legume',
                'saison' => 'ete',
                'ph_sol_min' => 6.0,
                'ph_sol_max' => 7.0,
                'temp_min' => 20,
                'temp_max' => 35,
                'besoin_eau_cycle' => 400,
                'soil_type' => 'limoneux',
                'conseils' => 'Plantation en pleine terre après les gelées. Arrosage régulier.',
            ],
            [
                'nom_commun' => 'Maïs',
                'nom_scientifique' => 'Zea mays',
                'type' => 'cereale',
                'saison' => 'ete',
                'ph_sol_min' => 6.0,
                'ph_sol_max' => 7.5,
                'temp_min' => 18,
                'temp_max' => 35,
                'besoin_eau_cycle' => 500,
                'soil_type' => 'argileux',
                'conseils' => 'Semis en ligne. Besoin en nutriments élevé.',
            ],
            [
                'nom_commun' => 'Haricot vert',
                'nom_scientifique' => 'Phaseolus vulgaris',
                'type' => 'legumineuse',
                'saison' => 'ete',
                'ph_sol_min' => 6.0,
                'ph_sol_max' => 7.5,
                'temp_min' => 15,
                'temp_max' => 30,
                'besoin_eau_cycle' => 300,
                'soil_type' => 'sableux',
                'conseils' => 'Ne pas arroser les feuilles. Tuteur recommandé.',
            ],
            [
                'nom_commun' => 'Carotte',
                'nom_scientifique' => 'Daucus carota',
                'type' => 'legume',
                'saison' => 'automne',
                'ph_sol_min' => 6.0,
                'ph_sol_max' => 6.8,
                'temp_min' => 10,
                'temp_max' => 25,
                'besoin_eau_cycle' => 350,
                'soil_type' => 'sableux',
                'conseils' => 'Sol meuble pour développement des racines.',
            ],
            [
                'nom_commun' => 'Salade',
                'nom_scientifique' => 'Lactuca sativa',
                'type' => 'legume',
                'saison' => 'printemps',
                'ph_sol_min' => 6.0,
                'ph_sol_max' => 7.0,
                'temp_min' => 10,
                'temp_max' => 24,
                'besoin_eau_cycle' => 250,
                'soil_type' => 'humifere',
                'conseils' => 'Arrosage fréquent. Protection contre les limaces.',
            ],
            [
                'nom_commun' => 'Blé',
                'nom_scientifique' => 'Triticum aestivum',
                'type' => 'cereale',
                'saison' => 'hiver',
                'ph_sol_min' => 6.0,
                'ph_sol_max' => 7.5,
                'temp_min' => 5,
                'temp_max' => 25,
                'besoin_eau_cycle' => 450,
                'soil_type' => 'argileux',
                'conseils' => 'Semis d\'automne. Récolte en été.',
            ],
            [
                'nom_commun' => 'Pomme de terre',
                'nom_scientifique' => 'Solanum tuberosum',
                'type' => 'legume',
                'saison' => 'printemps',
                'ph_sol_min' => 5.0,
                'ph_sol_max' => 6.5,
                'temp_min' => 10,
                'temp_max' => 25,
                'besoin_eau_cycle' => 400,
                'soil_type' => 'sableux',
                'conseils' => 'Buttage recommandé. Éviter les excès d\'eau.',
            ],
            [
                'nom_commun' => 'Mangue',
                'nom_scientifique' => 'Mangifera indica',
                'type' => 'fruit',
                'saison' => 'ete',
                'ph_sol_min' => 5.5,
                'ph_sol_max' => 7.5,
                'temp_min' => 20,
                'temp_max' => 38,
                'besoin_eau_cycle' => 600,
                'soil_type' => 'limoneux',
                'conseils' => 'Arrosage régulier en période sèche.',
            ],
        ];

        foreach ($cultures as $culture) {
            Culture::firstOrCreate(
                ['nom_commun' => $culture['nom_commun']],
                $culture
            );
        }
    }
}
