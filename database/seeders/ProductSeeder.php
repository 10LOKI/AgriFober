<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'nom_commercial' => 'Engrais NPK 15-15-15',
                'description' => 'Engrais complet équilibré pour céréales et légumes.',
                'composant_actif' => 'Azote 15%, Phosphate 15%, Potasse 15%',
                'dosage_recommande' => '300 kg/ha',
                'delai_avant_recolte' => 0,
                'type' => 'engrais',
                'avantages' => 'Stimule croissance racinaire et développement foliaire.',
                'usage_method' => 'Épandre à la volée ou en localisé avant semis.',
                'safety_instructions' => 'Port de gants recommandé. Ne pas inhaler.',
            ],
            [
                'nom_commercial' => 'Urée 46%',
                'description' => 'Engrais azoté à haute concentration.',
                'composant_actif' => 'Azote 46%',
                'dosage_recommande' => '150 kg/ha',
                'delai_avant_recolte' => 0,
                'type' => 'engrais',
                'avantages' => 'Rapide et efficace.',
                'usage_method' => 'Incorporer au sol pour éviter lessivage.',
                'safety_instructions' => 'Stockage à l\'abri de l\'humidité.',
            ],
            [
                'nom_commercial' => 'Fongicide Cuivrique',
                'description' => 'Protection contre les maladies fongiques mildiou, oïdium.',
                'composant_actif' => 'Hydroxyde de cuivre 25%',
                'dosage_recommande' => '2 kg/ha',
                'delai_avant_recolte' => 21,
                'type' => 'fongicide',
                'avantages' => 'Action préventive et curative.',
                'usage_method' => 'Diluer dans l\'eau et pulvériser.',
                'safety_instructions' => 'Éviter contact avec peau et yeux.',
            ],
            [
                'nom_commercial' => 'Insecticide Naturel Neem',
                'description' => 'Insecticide bio à base d\'huile de neem.',
                'composant_actif' => 'Huile de neem 5%',
                'dosage_recommande' => '1 L/ha',
                'delai_avant_recolte' => 0,
                'type' => 'biologique',
                'avantages' => 'Écologique, sans résidus.',
                'usage_method' => 'Pulvérisation foliaire en soirée.',
                'safety_instructions' => 'Agiter avant utilisation.',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['nom_commercial' => $product['nom_commercial']],
                $product
            );
        }
    }
}

