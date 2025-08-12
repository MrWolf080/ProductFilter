<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'property_values')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function propertyValues()
    {
        return $this->hasMany(PropertyValue::class, 'property_id');
    }
}
