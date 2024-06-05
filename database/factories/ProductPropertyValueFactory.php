<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPropertyValue;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPropertyValue>
 */
class ProductPropertyValueFactory extends Factory
{
    protected $model = ProductPropertyValue::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'property_id' => Property::factory(),
            'value' => $this->faker->word,
        ];
    }
}
