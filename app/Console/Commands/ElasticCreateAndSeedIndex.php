<?php

namespace App\Console\Commands;

use App\Services\ElasticService;
use Illuminate\Console\Command;
use App\Models\Product;

class ElasticCreateAndSeedIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:create-seed-index {index=products}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Elasticsearch index and seed products into it';

    protected $elastic;

    public function __construct(ElasticService $elastic)
    {
        parent::__construct();
        $this->elastic = $elastic;
    }

    public function handle()
    {
        $index = $this->argument('index');

        $this->info("Creating Elasticsearch index: {$index}");

        try {
            $this->elastic->createIndex($index);
            $this->info("Index created successfully.");
        } catch (\Exception $e) {
            $this->error("Failed to create index: " . $e->getMessage());
        }

        $this->info("Indexing products into Elasticsearch...");

        $products = Product::all();

        foreach ($products as $product) {
            try {
                $this->elastic->indexDocument($index, [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    // add more fields if needed
                ]);
                $this->info("Indexed product ID {$product->id}");
            } catch (\Exception $e) {
                $this->error("Failed to index product ID {$product->id}: " . $e->getMessage());
            }
        }

        $this->info("Done!");
        return 0;
    }
}
