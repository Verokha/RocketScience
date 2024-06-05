<?php

namespace App\Http\Services;

use App\Models\Product;

class ProductService
{
    public function list(array $properties)
    {
        $query = Product::with(['propertyValues.property']);
        if (count($properties)) {
            foreach ($properties as $propertyName => $values) {
                $query->whereHas('propertyValues.property', function ($q) use ($propertyName, $values) {
                    $q->where('name', $propertyName)
                      ->whereIn('value', $values);
                });
            }
        }
        return $query->paginate(40);
    }   
}
