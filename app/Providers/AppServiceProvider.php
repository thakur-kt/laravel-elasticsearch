<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
// Removed unused Elastic\Transport\ConnectionPool\ConnectionFactory, NodePoolBuilder, and NodePoolInterface imports
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Client\ClientInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // // PSR-18 HTTP client
        // $this->app->bind(ClientInterface::class, function () {
        //     return new GuzzleClient();
        // });

        // // NodePoolInterface binding required by elastic client
        // // NodePoolInterface binding removed as it's not required for basic Elasticsearch client usage.
        // // Elasticsearch client binding
        // $this->app->singleton(Client::class, function () {
        //     return ClientBuilder::create()
        //         ->setHosts(['localhost:9200']) // Adjust host/port as needed
        //         ->build();
        // });
    }

    public function boot()
    {
        //
    }
}
