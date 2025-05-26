<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ElasticService;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected ElasticService $elasticService;

    /**
     * Inject the custom ElasticService for Elasticsearch operations.
     */
    public function __construct(ElasticService $elasticService)
    {
        $this->elasticService = $elasticService;
    }

    /**
     * Example search method (not used in API, for demonstration).
     */
    public function search()
    {
        // Search for products with the name 'laptop' in Elasticsearch
        $results = $this->elasticService->search('products', 'name', 'laptop');

        return response()->json($results);
    }

    /**
     * List all products or search products by query.
     * If 'q' is present in the request, search Elasticsearch.
     * Otherwise, return all products from the database.
     */
    public function index(Request $request)
    {
        if ($request->filled('q')) {
            $query = $request->get('q', '');

            if (!$query) {
                return response()->json(['data' => []]);
            }
            // Search products in Elasticsearch using the query
            $response = $this->elasticService->search('products', $query);
            $hits = $response['hits']['hits'] ?? [];

            return response()->json([
                // Map Elasticsearch hits to product data
                'data' => collect($hits)->map(function ($hit) {
                    return $hit['_source'];
                }),
            ]);
        }

        // Return all products from the database if no search query
        return response()->json(['data' => Product::limit(10)->orderBy('id', 'desc')->get()]);
    }

    /**
     * Display a single product by its ID.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    /**
     * Store a new product in the database.
     * (Elasticsearch indexing can be added here if needed)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'sometimes|numeric',
        ]);

        $product = Product::create($data);
        return response()->json($product, 201);
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'sometimes|numeric',
        ]);

        $product->update($data);

        return response()->json($product);
    }

    /**
     * Delete a product from the database and Elasticsearch.
     */
    public function destroy(Product $product)
    {
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete(); // Delete from DB

        // Delete from Elasticsearch index as well
        try {
            app(ElasticService::class)->deleteDocument('products', $product->id);
        } catch (\Exception $e) {
            // Log the exception if needed
            Log::error('Failed to delete from Elasticsearch: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
