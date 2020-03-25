<?php

namespace Service\Elasticsearch;

use Elasticsearch\Client;

interface ElasticsearchServiceInterface
{
    public const DIC_NAME = 'Service.Elasticsearch.ElasticsearchServiceInterface';

    public function getClient(): Client;
}
