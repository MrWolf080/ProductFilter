<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyValue>
 */
class PropertyValueFactory extends Factory
{
    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'property_id' => \App\Models\ProductProperty::factory(),
            'value' => $this->faker->word,
        ];
    }
}
