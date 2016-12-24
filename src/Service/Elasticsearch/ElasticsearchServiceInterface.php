<?php

namespace Service\Elasticsearch;

use Elasticsearch\Client;

interface ElasticsearchServiceInterface
{
    const DIC_NAME = 'Service.Elasticsearch.ElasticsearchServiceInterface';

    /**
     * @return Client
     */
    public function getClient();
}
