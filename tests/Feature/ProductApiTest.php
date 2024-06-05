<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductPropertyValue;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testProducts(): void
    {
        $product = Product::factory()->create();
        
        $colorProperty = Property::factory()->create();
        $materialProperty = Property::factory()->create();
        
        ProductPropertyValue::factory()->create([
            'product_id' => $product->id,
            'property_id' => $colorProperty->id,
            'value' => 'Черный'
        ]);
        ProductPropertyValue::factory()->create([
            'product_id' => $product->id,
            'property_id' => $colorProperty->id,
            'value' => 'Зеленый'
        ]);
        ProductPropertyValue::factory()->create([
            'product_id' => $product->id,
            'property_id' => $colorProperty->id,
            'value' => 'Красный'
        ]);
        ProductPropertyValue::factory()->create([
            'product_id' => $product->id,
            'property_id' => $materialProperty->id,
            'value' => 'Металл'
        ]);
        $response = $this->get('/api/products');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'quantity',
                    'properties' => [
                        '*' => [
                            'type',
                            'items' => [
                                '*' => ['value']
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $response->assertJsonFragment([
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'properties' => [
                [
                    'type' => $colorProperty->name,
                    'items' => [
                        ['value' => 'Черный'],
                        ['value' => 'Зеленый'],
                        ['value' => 'Красный']
                    ]
                ],
                [
                    'type' => $materialProperty->name,
                    'items' => [
                        ['value' => 'Металл']
                    ]
                ]
            ]
        ]);
    }

    public function testProductsFilteredByProperties()
    {
        // Создаем тестовые данные
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        
        $colorProperty = Property::factory()->create(['name' => 'Цвет']);
        $materialProperty = Property::factory()->create(['name' => 'Материал']);
        
        ProductPropertyValue::factory()->create([
            'product_id' => $product1->id,
            'property_id' => $colorProperty->id,
            'value' => 'Черный'
        ]);
        ProductPropertyValue::factory()->create([
            'product_id' => $product1->id,
            'property_id' => $materialProperty->id,
            'value' => 'Металл'
        ]);
        
        ProductPropertyValue::factory()->create([
            'product_id' => $product2->id,
            'property_id' => $colorProperty->id,
            'value' => 'Зеленый'
        ]);
        ProductPropertyValue::factory()->create([
            'product_id' => $product2->id,
            'property_id' => $materialProperty->id,
            'value' => 'Дерево'
        ]);

        // Фильтр по цвету "Черный"
        $response = $this->get('/api/products?properties[Цвет][]=Черный');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $product1->name
        ]);
        $response->assertJsonMissing([
            'name' => $product2->name
        ]);

        // Фильтр по материалу "Дерево"
        $response = $this->get('/api/products?properties[Материал][]=Дерево');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $product2->name
        ]);
        $response->assertJsonMissing([
            'name' => $product1->name
        ]);

        // Фильтр по цвету "Зеленый" и материалу "Дерево"
        $response = $this->get('/api/products?properties[Цвет][]=Зеленый&properties[Материал][]=Дерево');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $product2->name
        ]);
        $response->assertJsonMissing([
            'name' => $product1->name
        ]);
    }
}
