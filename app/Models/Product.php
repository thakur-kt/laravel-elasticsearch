<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Laravel\Scout\Searchable;
class Product extends Model
{
    use HasFactory,Searchable;


    protected $fillable = ['name', 'description', 'price', 'stock'];

//     protected static function booted()
// {
//     static::saved(function ($product) {
//         app(ElasticService::class)->indexDocument('products', [
//             'id' => $product->id,
//             'name' => $product->name,
//             'description' => $product->description,
//         ]);
//     });
// }
    public function toSearchableArray(): array
    {
        return $this->only(['id', 'name', 'description', 'price', 'stock']);
    }
    

}
