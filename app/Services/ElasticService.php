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
            ->setHosts(['localhost:9200']) // change if needed
            ->build();
    }

    public function indexDocument(string $index, array $data): array
    {
        return $this->client->index([
            'index' => $index,
            'body' => $data,
        ])->asArray();
    }

    public function search(string $index, string $field, string $query): array
    {
        return $this->client->search([
            'index' => $index,
            'body' => [
                'query' => [
                    'match' => [
                        $field => $query,
                    ],
                ],
            ],
        ])->asArray();
    }

    public function deleteIndex(string $index): array
    {
        return $this->client->indices()->delete(['index' => $index])->asArray();
    }

    public function createIndex(string $index, array $settings = []): array
    {
        return $this->client->indices()->create([
            'index' => $index,
            'body' => $settings,
        ])->asArray();
    }
}
