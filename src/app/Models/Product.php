<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'quantity'];

    public function properties()
    {
        return $this->belongsToMany(ProductProperty::class, 'property_values')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function getPropertyValue($propertyName)
    {
        $property = $this->properties()->where('name', $propertyName)->first();
        return $property ? $property->pivot->value : null;
    }
}
