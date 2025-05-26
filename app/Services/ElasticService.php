<?php

namespace App\Services;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

/**
 * ElasticService
 * ---------------
 * A custom service class for interacting with Elasticsearch.
 * Provides methods for indexing, updating, deleting, searching documents and managing indices.
 */
class ElasticService
{
    protected Client $client;

    /**
     * Initialize the Elasticsearch client using the host from .env.
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([env('ELASTICSEARCH_HOST', 'localhost:9200')]) // Set Elasticsearch host
            ->build();
    }

    /**
     * Index (create or update) a document in the specified index.
     * @param string $index
     * @param array $data
     * @return array
     */
    public function indexDocument(string $index, array $data): array
    {
        return  $this->client->index([
            'index' => $index,
            'id' => $data['id'],
            'body' => $data
        ])->asArray();
    }

    /**
     * Update a document in the specified index.
     * @param string $index
     * @param array $data
     * @return mixed
     */
    public function updateDocument(string $index, array $data)
    {
        $params = [
            'index' => $index,
            'id' => $data['id'],
            'body' => [
                'doc' => $data,
            ],
        ];

        return $this->client->update($params);
    }

    /**
     * Delete a document from the specified index by ID.
     * @param $index
     * @param $id
     */
    public function deleteDocument($index, $id)
    {
        $this->client->delete([
            'index' => $index,
            'id' => $id
        ]);
    }

    // public function search(string $index, string $field, string $query): array
    // {
    //     return $this->client->search([
    //         'index' => $index,
    //         'body' => [
    //             'query' => [
    //                 'match' => [
    //                     $field => $query,
    //                 ],
    //             ],
    //         ],
    //     ])->asArray();
    // }
    public function search($index, $query)
    {
        return $this->client->search([
            'index' => $index,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['name', 'description']
                    ]
                ]
            ]
        ])->asArray();
    }

    /**
     * Delete an index by name.
     * @param string $index
     * @return array
     */
    public function deleteIndex(string $index): array
    {
        return $this->client->indices()->delete(['index' => $index])->asArray();
    }

    /**
     * Delete all indices associated with an alias, or the index itself if not an alias.
     * @param string $index
     */
    public function deleteAllIndex(string $index)
    {
        // Check if $index is an alias
        $client = $this->client;
        $aliasResponse = $client->indices()->getAlias(['name' => $index]);
        $indices = array_keys($aliasResponse->asArray());

        if (!empty($indices)) {
            // Delete all indices that the alias points to
            foreach ($indices as $realIndex) {
                logger("Deleting index: $realIndex");
                $client->indices()->delete(['index' => $realIndex]);
            }
        } else {
            // If not an alias, try to delete as index
            $client->indices()->delete(['index' => $index]);
        }
    }

    /**
     * Create a new index with the specified settings.
     * @param string $index
     * @param array $settings
     * @return array
     */
    public function createIndex(string $index, array $settings = []): array
    {
        return $this->client->indices()->create([
            'index' => $index,
            'body' => $settings,
        ])->asArray();
    }
}
