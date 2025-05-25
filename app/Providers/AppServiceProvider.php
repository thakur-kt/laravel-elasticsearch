<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Transport\ConnectionPool\ConnectionFactory;
use Elastic\Transport\NodePool\NodePoolBuilder;
use Elastic\Transport\NodePool\NodePoolInterface;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
     // PSR-18 HTTP client
        // $this->app->bind(ClientInterface::class, function () {
        //     return new GuzzleAdapter(new GuzzleClient());
        // });

        // // NodePoolInterface binding required by elastic client
        // $this->app->bind(NodePoolInterface::class, function () {
        //     $factory = new ConnectionFactory();
        //     $builder = new NodePoolBuilder($factory);

        //     return $builder->create([
        //         ['host' => 'localhost', 'port' => 9200], // Modify to your ES config
        //     ]);
        // });
    }

    public function boot()
    {
        //
    }
}
