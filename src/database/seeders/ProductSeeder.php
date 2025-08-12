<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\PropertyValue;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Создаем свойства товаров
        $properties = [
            'Цвет плафона' => ['белый', 'черный', 'красный', 'синий'],
            'Цвет арматуры' => ['серебро', 'золото', 'черный', 'белый'],
            'Бренд' => ['Xiaomi', 'Ikea', 'Philips', 'Osram'],
            'Материал' => ['пластик', 'металл', 'стекло', 'дерево'],
        ];

        // Создаем записи свойств
        foreach ($properties as $name => $values) {
            ProductProperty::create(['name' => $name]);
        }

        // Создаем 100 товаров
        Product::factory(1000)->create()->each(function ($product) use ($properties) {
            // Для каждого товара добавляем все свойства со случайными значениями
            foreach ($properties as $propertyName => $values) {
                $property = ProductProperty::where('name', $propertyName)->first();
                $randomValue = $values[array_rand($values)];

                PropertyValue::create([
                    'product_id' => $product->id, // Используем ID текущего продукта
                    'product_property_id' => $property->id,
                    'value' => $randomValue,
                ]);
            }
        });
    }
}
