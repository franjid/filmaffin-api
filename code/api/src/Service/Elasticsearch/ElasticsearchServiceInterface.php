<?php

namespace App\Service\Elasticsearch;

use Elasticsearch\Client;

interface ElasticsearchServiceInterface
{
    public function getClient(): Client;
}
