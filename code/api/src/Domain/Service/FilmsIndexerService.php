<?php

namespace App\Domain\Service;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\FilmAttribute;
use App\Domain\Entity\FilmParticipant;
use App\Domain\Exception\IndexInconsistencyException;
use App\Domain\Helper\FilmImageHelper;
use App\Domain\Helper\StringHelper;
use App\Domain\Interfaces\FilmsIndexerInterface;
use App\Infrastructure\Interfaces\ElasticsearchServiceInterface;
use Elasticsearch\Client;
use Exception;
use JsonException;

class FilmsIndexerService implements FilmsIndexerInterface
{
    private Client $elasticsearchClient;
    private string $elasticsearchIndexName;
    private string $elasticsearchTypeFilm;
    private array $indexParams;
    private StringHelper $stringHelper;
    private FilmImageHelper $filmImageHelper;

    public function __construct(
        ElasticsearchServiceInterface $elasticsearch,
        string $elasticsearchIndexName,
        string $elasticsearchTypeFilm,
        StringHelper $stringHelper,
        FilmImageHelper $filmImageHelper
    )
    {
        $this->elasticsearchClient = $elasticsearch->getClient();
        $this->elasticsearchIndexName = $elasticsearchIndexName;
        $this->elasticsearchTypeFilm = $elasticsearchTypeFilm;
        $this->stringHelper = $stringHelper;
        $this->filmImageHelper = $filmImageHelper;

        $this->indexParams = [
            'index' => $elasticsearchIndexName . '_' . time(),
        ];
    }

    public function createMapping(): void
    {
        $mapping = [
            'properties' => [
                'idFilm' => ['type' => 'long'],
                'suggest' => [
                    'type' => 'completion',
                ],
                'title' => [
                    'type' => 'text',
                    'analyzer' => 'spanish',
                ],
                'originalTitle' => [
                    'type' => 'text',
                    'analyzer' => 'english',
                ],
                'rating' => ['type' => 'float'],
                'numRatings' => ['type' => 'integer'],
                'popularityRanking' => ['type' => 'integer'],
                'year' => ['type' => 'integer'],
                'duration' => ['type' => 'integer'],
                'country' => [
                    'type' => 'keyword',
                    'index' => 'false',
                ],
                'inTheatres' => [
                    'type' => 'boolean',
                ],
                'releaseDate' => [
                    'type' => 'date',
                ],
                'directors' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'actors' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'posterImages' => [
                    'type' => 'object',
                ],
                'synopsis' => [
                    'type' => 'keyword',
                    'index' => 'false',
                ],
                'topics' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'screenplayers' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'musicians' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'cinematographers' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
            ],
        ];

        $indexParams = $this->indexParams;
        $indexParams['body'] = [
            'mappings' => [
                $this->elasticsearchTypeFilm => $mapping,
            ],
        ];

        $this->elasticsearchClient->indices()->create($indexParams);
    }

    public function index(FilmCollection $films): void
    {
        $this->indexParams['type'] = $this->elasticsearchTypeFilm;
        $this->indexParams['body'] = '';

        foreach ($films->getItems() as $film) {
            $numRatings = $film->getNumRatings() ?: 0;

            $filmForIndex = [
                'idFilm' => $film->getIdFilm(),
                'suggest' => [
                    'input' => array_values(
                        array_unique(
                            array_merge(
                                $this->stringHelper->getSanitizedWordPermutations($film->getTitle()),
                                $this->stringHelper->getSanitizedWordPermutations($film->getOriginalTitle())
                            )
                        )
                    ),
                    'weight' => $numRatings,
                ],
                'title' => $film->getTitle(),
                'originalTitle' => $film->getOriginalTitle(),
                'rating' => $film->getRating(),
                'numRatings' => $numRatings,
                'popularityRanking' => $film->getPopularityRanking(),
                'year' => $film->getYear(),
                'duration' => $film->getDuration(),
                'country' => $film->getCountry(),
                'inTheatres' => $film->isInTheatres(),
                'releaseDate' => $film->getReleaseDate(),
                'directors' => array_column($film->getDirectors()->toArray(), FilmParticipant::FIELD_NAME),
                'actors' => array_column($film->getActors()->toArray(), FilmParticipant::FIELD_NAME),
                'posterImages' => $this->filmImageHelper->getImagePosters($film->getIdFilm()),
                'synopsis' => $film->getSynopsis() ?? '',
                'topics' => array_column($film->getTopics()->toArray(), FilmAttribute::FIELD_NAME),
                'screenplayers' => array_column($film->getScreenplayers()->toArray(), FilmParticipant::FIELD_NAME),
                'musicians' => array_column($film->getMusicians()->toArray(), FilmParticipant::FIELD_NAME),
                'cinematographers' => array_column($film->getCinematographers()->toArray(), FilmParticipant::FIELD_NAME),
            ];

            $this->indexParams['body'] .= '{ "index" : { "_id" : "' . $film->getIdFilm() . '" } }' . "\n";

            try {
                $this->indexParams['body'] .= json_encode($filmForIndex, JSON_THROW_ON_ERROR) . "\n";
            } catch (JsonException $e) {
                trigger_error('Json malformed. Film id: ' . $film->getIdFilm());
            }
        }

        $this->elasticsearchClient->bulk($this->indexParams);
    }

    public function deletePreviousIndexes(): void
    {
        $previousIndexes = $this->getPreviousIndexes();

        if (!$previousIndexes) {
            return;
        }

        $indexesNames = array_keys($previousIndexes);

        foreach ($indexesNames as $indexName) {
            if ($indexName !== $this->indexParams['index'] && substr_count($indexName, $this->elasticsearchIndexName)) {
                $deleteParams['index'] = $indexName;
                $this->elasticsearchClient->indices()->delete($deleteParams);
            }
        }
    }

    private function getPreviousIndexes(): ?array
    {
        try {
            $indexes = $this->elasticsearchClient->indices()->getMapping();
        } catch (Exception $e) {
            $indexes = null;
        }

        return $indexes;
    }

    public function createIndexAlias(): void
    {
        $aliasParams['index'] = $this->indexParams['index'];
        $aliasParams['name'] = $this->elasticsearchIndexName;
        $this->elasticsearchClient->indices()->putAlias($aliasParams);
    }

    public function getLastIndexName(): string
    {
        $indexesNames = array_keys($this->getPreviousIndexes());

        if (count($indexesNames) > 1) {
            throw new IndexInconsistencyException(
                'There are more than 1 index, this could cause problems. Run filmaffin:index:films to clean up'
            );
        }

        return end($indexesNames);
    }

    public function setCurrentIndexName(string $indexName): void
    {
        $this->indexParams['index'] = $indexName;
    }
}
