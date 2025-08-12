<?php

namespace App\Http\Controllers;

use App\Models\ProductProperty;
use App\Models\PropertyValue;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['properties' => function($query) {
            $query->select('product_properties.id', 'name')
                ->withPivot('value');
        }]);

        // Фильтрация по свойствам
        if ($request->has('properties')) {
            $properties = $request->input('properties');

            foreach ($properties as $propertyName => $values) {
                if (!is_array($values)) {
                    $values = [$values];
                }

                $query->whereHas('properties', function($q) use ($propertyName, $values) {
                    $q->where('name', $propertyName)
                        ->whereIn('value', $values);
                });
            }
        }

        // Получаем доступные свойства для фильтров
        $availableProperties = ProductProperty::all()->map(function($property) {
            $values = PropertyValue::where('product_property_id', $property->id)
                ->distinct()
                ->pluck('value')
                ->sort()
                ->values();

            $property->values = $values;
            return $property;
        });

        // Пагинация
        $perPage = $request->input('per_page', 40);
        $products = $query->paginate($perPage);

        return view('catalog', [
            'products' => $products,
            'availableProperties' => $availableProperties,
        ]);
    }
}
