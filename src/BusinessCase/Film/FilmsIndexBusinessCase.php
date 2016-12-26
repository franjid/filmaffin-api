<?php

namespace BusinessCase\Film;

use Service\Elasticsearch\ElasticsearchServiceInterface;
use Elasticsearch\Client;
use Entity\Film;
use Exception;

class FilmsIndexBusinessCase implements FilmsIndexBusinessCaseInterface
{
    /** @var Client $elasticsearchClient */
    private $elasticsearchClient;

    /** @var string $elasticsearchIndexName */
    private $elasticsearchIndexName;

    /** @var string $elasticsearchTypeFilm */
    private $elasticsearchTypeFilm;

    /** @var array $indexParams */
    private $indexParams;

    /**
     * @param ElasticsearchServiceInterface $elasticsearch
     * @param $elasticsearchIndexName
     * @param $elasticsearchTypeFilm
     */
    public function __construct(
        ElasticsearchServiceInterface $elasticsearch,
        $elasticsearchIndexName,
        $elasticsearchTypeFilm
    )
    {
        $this->elasticsearchClient = $elasticsearch->getClient();
        $this->elasticsearchIndexName = $elasticsearchIndexName;
        $this->elasticsearchTypeFilm = $elasticsearchTypeFilm;

        $this->indexParams = [
            'index' => $elasticsearchIndexName . '_' . time(),
        ];
    }

    public function createMapping()
    {
        $mapping = [
            'properties' => [
                'idFilm' => ['type' => 'long'],
                'title' => [
                    'type' => 'string',
                    'analyzer' => 'spanish',
                ],
                'originalTitle' => [
                    'type' => 'string',
                    'analyzer' => 'english',
                ],
                'numRatings' => ['type' => 'integer']
            ]
        ];

        $indexParams = $this->indexParams;
        $indexParams['body']['mappings'][$this->elasticsearchTypeFilm] = $mapping;
        $this->elasticsearchClient->indices()->create($indexParams);
    }

    public function index(array $films)
    {
        $this->indexParams['type'] = $this->elasticsearchTypeFilm;
        $this->indexParams['body'] = '';

        /** @var Film $film */
        foreach ($films as $film) {
            $filmForIndex = [
                'idFilm' => $film->getIdFilm(),
                'title' => $film->getTitle(),
                'originalTitle' => $film->getOriginalTitle(),
                'numRatings' => $film->getNumRatings()
            ];

            $this->indexParams['body'] .= '{ "index" : { "_id" : "' . $film->getIdFilm() . '" } }' . "\n";
            $this->indexParams['body'] .= json_encode($filmForIndex, JSON_NUMERIC_CHECK) . "\n";
        }

        try {
            $this->elasticsearchClient->bulk($this->indexParams);
        } catch (Exception $e) {
            print_r($this->indexParams);
            throw new Exception($e);
        }
    }

    public function deletePreviousIndexes()
    {
        $previousIndexes = $this->getPreviousIndexes();
        $indexesNames = array_keys($previousIndexes);

        foreach ($indexesNames as $indexName) {
            if (substr_count($indexName, $this->elasticsearchIndexName) && $indexName != $this->indexParams['index']) {
                $deleteParams['index'] = $indexName;
                $this->elasticsearchClient->indices()->delete($deleteParams);
            }
        }
    }

    public function createIndexAlias()
    {
        $aliasParams['index'] = $this->indexParams['index'];
        $aliasParams['name'] = $this->elasticsearchIndexName;
        $this->elasticsearchClient->indices()->putAlias($aliasParams);
    }

    private function getPreviousIndexes()
    {
        try {
            $indexes = $this->elasticsearchClient->indices()->getMapping();
        } catch (Exception $e) {
            $indexes = null;
        }

        return $indexes;
    }
}
