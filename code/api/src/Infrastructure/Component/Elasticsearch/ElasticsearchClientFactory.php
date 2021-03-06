<?php

namespace App\Infrastructure\Component\Elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticsearchClientFactory
{
    public function createClient(array $hosts): Client
    {
        return ClientBuilder::create()->setHosts($hosts)->build();
    }
}
