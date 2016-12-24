<?php

namespace AppBundle\Controller;

use Service\Elasticsearch\ElasticsearchServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Elasticsearch\Client;

class IndexController extends BaseController
{
    public function indexAction()
    {
        /** @var ElasticsearchServiceInterface $elasticsearch */
        $elasticsearchService = $this->get(ElasticsearchServiceInterface::DIC_NAME);

        /** @var Client $elasticSearchClient */
        $elasticSearchClient = $elasticsearchService->getClient();

        $params = [
            'index' => 'my_index'
        ];

        $response = $elasticSearchClient->indices()->delete($params);
        print_r($response);
        $response = $elasticSearchClient->indices()->create($params);
        print_r($response);


        return new JsonResponse(['test' => 1], JsonResponse::HTTP_OK);
    }
}
