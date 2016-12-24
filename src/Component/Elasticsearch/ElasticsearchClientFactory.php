<?php

namespace Component\Elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticsearchClientFactory
{
    /**
     * @param array $hosts
     *
     * @return Client
     */
    public function createClient(array $hosts)
    {
        return ClientBuilder::create()->setHosts($hosts)->build();
    }
}
