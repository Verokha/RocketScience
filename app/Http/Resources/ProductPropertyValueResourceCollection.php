<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductPropertyValueResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        $grouped = $this->collection->groupBy('property.name');
        return $grouped->map(function ($properties, $type) {
            return [
                'type' => $type,
                'items' => $properties->map(function ($property) {
                    return new PropertyResource($property);
                }),
            ];
        })->values()->toArray();
    }
}
