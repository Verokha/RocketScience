<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 
        'price', 
        'quantity',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function propertyValues()
    {
        return $this->hasMany(ProductPropertyValue::class, 'product_id', 'id');
    }
}
