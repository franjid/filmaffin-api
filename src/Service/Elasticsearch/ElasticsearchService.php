<?php

namespace Service\Elasticsearch;

use Elasticsearch\Client;

class ElasticsearchService implements ElasticsearchServiceInterface
{
    /** @var Client $client */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getClient()
    {
        return $this->client;
    }
}
