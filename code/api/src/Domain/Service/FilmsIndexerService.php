<?php

namespace App\Domain\Service;

use App\Application\Command\IndexFilmsCommand;
use App\Domain\Entity\Collection\FilmCollection;
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
    private array $indexParams;
    private StringHelper $stringHelper;
    private FilmImageHelper $filmImageHelper;

    public function __construct(
        ElasticsearchServiceInterface $elasticsearch,
        string $elasticsearchIndexName,
        StringHelper $stringHelper,
        FilmImageHelper $filmImageHelper
    )
    {
        $this->elasticsearchClient = $elasticsearch->getClient();
        $this->elasticsearchIndexName = $elasticsearchIndexName;
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
                    'search_analyzer' => 'standard',
                    'analyzer' => 'standard',
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
                'numFrames' => ['type' => 'integer'],
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
                'genres' => [
                    'type' => 'keyword',
                    'index' => 'true',
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
                'proReviews' => [
                    'type' => 'object',
                ],
                'userReviews' => [
                    'type' => 'object',
                ],
                'platforms' => [
                    'type' => 'object',
                ],
            ],
        ];

        $indexParams = $this->indexParams;
        $indexParams['body'] = [
            'mappings' => $mapping,
        ];

        $this->elasticsearchClient->indices()->create($indexParams);
    }

    public function index(FilmCollection $films): void
    {
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
                'numFrames' => $film->getNumFrames(),
                'popularityRanking' => $film->getPopularityRanking(),
                'year' => $film->getYear(),
                'duration' => $film->getDuration(),
                'country' => $film->getCountry(),
                'inTheatres' => $film->isInTheatres(),
                'releaseDate' => $film->getReleaseDate(),
                'directors' => $film->getDirectors()->toArray(),
                'actors' => $film->getActors()->toArray(),
                'posterImages' => $this->filmImageHelper->getImagePosters($film->getIdFilm()),
                'synopsis' => $film->getSynopsis() ?? '',
                'genres' => $film->getGenres()->toArray(),
                'topics' => $film->getTopics()->toArray(),
                'screenplayers' => $film->getScreenplayers()->toArray(),
                'musicians' => $film->getMusicians()->toArray(),
                'cinematographers' => $film->getCinematographers()->toArray(),
                'proReviews' => $film->getProReviews()->toArray(),
                'userReviews' => $film->getUserReviews()->toArray(),
                'platforms' => $film->getPlatforms()->toArray(),
            ];

            $this->indexParams['body'] .= '{ "index" : { "_id" : "' . $film->getIdFilm() . '" } }' . "\n";

            try {
                $this->indexParams['body'] .= json_encode($filmForIndex, JSON_THROW_ON_ERROR) . "\n";
            } catch (JsonException $e) {
                trigger_error('Json malformed. Film id: ' . $film->getIdFilm());
            }
        }

        $result = $this->elasticsearchClient->bulk($this->indexParams);

        if ((boolean) $result['errors'] === true) {
            throw new \Exception($result['items'][0]['index']['error']['reason']);
        }
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
        $projectIndexes = 0;
        $lastIndexName = null;

        foreach ($indexesNames as $indexName) {
            if (strpos($indexName, $this->elasticsearchIndexName) !== false) {
                $lastIndexName = $indexName;
                $projectIndexes++;
            }
        }

        if ($projectIndexes > 1) {
            throw new IndexInconsistencyException(
                sprintf(
                    'There are more than 1 %s index, this could cause inconsistency problems. Run %s to clean up',
                    $this->elasticsearchIndexName,
                    IndexFilmsCommand::COMMAND_NAME
                )
            );
        }

        return $lastIndexName;
    }

    public function setCurrentIndexName(string $indexName): void
    {
        $this->indexParams['index'] = $indexName;
    }
}
