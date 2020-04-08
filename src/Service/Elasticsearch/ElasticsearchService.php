<?php

namespace App\Service\Elasticsearch;

use Elasticsearch\Client;

class ElasticsearchService implements ElasticsearchServiceInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
