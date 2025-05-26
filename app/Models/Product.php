<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ElasticService;

class Product extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = ['name', 'description', 'price', 'stock'];

    /**
     * Booted method to handle model events.
     * Automatically index or update the product in Elasticsearch
     * whenever a product is created or updated.
     */
    protected static function booted()
    {
        // When a product is created, index it in Elasticsearch
        static::created(function ($product) {
            app(ElasticService::class)->indexDocument('products', $product->toSearchableArray());
        });

        // When a product is updated, re-index it in Elasticsearch
        static::updated(function ($product) {
            app(ElasticService::class)->updateDocument('products', $product->toSearchableArray());
        });
    }

    /**
     * Prepare the product data for Elasticsearch indexing.
     * Only include the specified fields.
     */
    public function toSearchableArray(): array
    {
        return $this->only(['id', 'name', 'description', 'price', 'stock']);
    }
}
