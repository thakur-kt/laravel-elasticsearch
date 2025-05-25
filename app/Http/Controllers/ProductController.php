<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ElasticService;

class ProductController extends Controller
{
    protected ElasticService $elasticService;

    public function __construct(ElasticService $elasticService)
    {
        $this->elasticService = $elasticService;
    }

    public function search()
    {
        $results = $this->elasticService->search('products', 'name', 'laptop');

        return response()->json($results);
    }

    public function index(Request $request)
    {
        if ($request->filled('search')) {
            return $this->elasticService->search('products', 'name', $request->input('search'));
        }

        return Product::all();
    }

    public function store(Request $request)
    {
        $product = Product::create($request->only(['name', 'description', 'price', 'stock']));
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only(['name', 'description', 'price', 'stock']));
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
