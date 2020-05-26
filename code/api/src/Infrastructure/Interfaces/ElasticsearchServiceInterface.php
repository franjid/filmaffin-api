<?php

namespace App\Infrastructure\Interfaces;

use Elasticsearch\Client;

interface ElasticsearchServiceInterface
{
    public function getClient(): Client;
}
