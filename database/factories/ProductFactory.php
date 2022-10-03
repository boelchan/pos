<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'supplier_id' => fake()->numberBetween(0, 100),
            'sku' => str()->random(4),
            'name' => fake()->name(),
            'price' => fake()->randomNumber(6),
            'qty' => fake()->randomNumber(3),
            'description' => fake()->paragraph(),
            'user_id' => 1,
        ];
    }
}
