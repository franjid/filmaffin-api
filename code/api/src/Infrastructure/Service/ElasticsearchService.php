<?php

namespace App\Infrastructure\Service;

use App\Infrastructure\Interfaces\ElasticsearchServiceInterface;
use Elasticsearch\Client;

class ElasticsearchService implements ElasticsearchServiceInterface
{
    public function __construct(
        protected Client $client
    ) {
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
