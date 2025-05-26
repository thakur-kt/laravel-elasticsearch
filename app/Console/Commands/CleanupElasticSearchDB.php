<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\ElasticService; // Import the ElasticService class

class CleanupElasticSearchDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-elastic-search-d-b';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove stale product data from Elasticsearch that no longer exists in the database';

    protected ElasticService $elastic;

    /**
     * Inject the custom ElasticService for Elasticsearch operations.
     */
    public function __construct(ElasticService $elastic)
    {
        parent::__construct();
        $this->elastic = $elastic;
    }

    /**
     * Handle the command execution.
     * Syncs Elasticsearch with the database by removing stale product records.
     */
    public function handle()
    {
        $this->info('Syncing Elasticsearch with DB...');

        // Step 1: Get all product IDs from the database
        $dbIds = Product::pluck('id')->toArray();

        // Step 2: Get all product IDs from Elasticsearch
        $response = $this->elastic->searchData([
            'index' => 'products',
            '_source' => false,
            'size' => 10000, // adjust based on expected count
            'query' => [
                'match_all' => (object)[], // Match all documents
            ]
        ]);

        // Extract IDs from Elasticsearch hits
        $esIds = array_map(fn ($hit) => $hit['_id'], $response['hits']['hits']);

        // Step 3: Find IDs present in Elasticsearch but not in the database (stale)
        $staleIds = array_diff($esIds, $dbIds);

        $this->info('Found ' . count($staleIds) . ' stale records in Elasticsearch.');
        if (empty($staleIds)) {
            $this->info('No stale records found.');
            return 0;
        }

        // Step 4: Prepare bulk delete body for Elasticsearch
        $body = [];
        foreach ($staleIds as $id) {
            $body[] = [
                'delete' => [
                    '_index' => 'products',
                    '_id' => $id,
                ],
            ];
        }

        // Perform bulk delete operation in Elasticsearch
        $this->elastic->bulk($body);

        $this->info('Deleted ' . count($staleIds) . ' stale records from Elasticsearch.');
        return 0;
    }
}
