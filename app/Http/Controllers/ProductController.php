<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Services\ProductService;

class ProductController extends Controller
{
    public function index(ProductService $service)
    {
        $result = $service->list(request()->get('properties', []));
        return ProductResource::collection($result);
    }
}
