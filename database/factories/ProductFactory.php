<?php

namespace Database\Factories;

use App\Enums\ProductTypeEnum;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom_commercial'      => ucfirst($this->faker->unique()->words(2, true)),
            'description'         => $this->faker->sentence(12),
            'composant_actif'    => ucfirst($this->faker->word()),
            'dosage_recommande'   => $this->faker->numberBetween(1, 10) . ' L/ha',
            'delai_avant_recolte' => $this->faker->numberBetween(0, 30),
            'type'                => $this->faker->randomElement(ProductTypeEnum::cases())->value,
            'avantages'           => $this->faker->sentence(8),
            'usage_method'        => $this->faker->sentence(10),
            'safety_instructions' => $this->faker->sentence(10),
            'image'               => null,
        ];
    }

    /**
     * State: a specific product type.
     */
    public function type(ProductTypeEnum $type): static
    {
        return $this->state(fn() => ['type' => $type->value]);
    }
}
