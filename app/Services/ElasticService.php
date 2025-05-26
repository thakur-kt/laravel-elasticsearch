<?php

namespace App\Services;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([env('ELASTICSEARCH_HOST', 'localhost:9200')]) // change if needed
            ->build();
    }

    public function indexDocument(string $index, array $data): array
    {
        return  $this->client->index([
            'index' => $index,
            'id' => $data['id'],
            'body' => $data
        ])->asArray();
    }

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

    public function deleteDocument($index, $id)
    {
        $this->client->delete([
            'index' => $index,
            'id' => $id
        ]);
    }

    public function searchData($data): array
    {
        return $this->client->search($data)->asArray();
    }
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

    public function deleteIndex(string $index): array
    {
        return $this->client->indices()->delete(['index' => $index])->asArray();
    }

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

    public function createIndex(string $index, array $settings = []): array
    {
        return $this->client->indices()->create([
            'index' => $index,
            'body' => $settings,
        ])->asArray();
    }

    /**
     * Run a bulk operation.
     *
     * @param array $operations
     * @return array
     */
    public function bulk(array $operations): array
    {
        return $this->client->bulk([
            'body' => $operations
        ])->asArray();
    }
}
