<?php

namespace Tests\Unit\Domain\Service;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Film;
use App\Domain\Helper\FilmImageHelper;
use App\Domain\Helper\StringHelper;
use App\Domain\Service\FilmsIndexerService;
use App\Infrastructure\Interfaces\ElasticsearchServiceInterface;
use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use PHPUnit\Framework\TestCase;

class FilmsIndexerServiceTest extends TestCase
{
    private const ELASTIC_SEARCH_INDEX_NAME = 'filmaffin_test';
    private const ELASTIC_SEARCH_TYPE_FILM = 'film_test';

    private Client $elasticsearchClientMock;
    private StringHelper $stringHelperMock;
    private FilmImageHelper $filmImageHelperMock;

    private FilmsIndexerService $filmsIndexerService;

    protected function setUp(): void
    {
        parent::setUp();

        $elasticsearchServiceMock = $this->createMock(ElasticsearchServiceInterface::class);
        $this->elasticsearchClientMock = $this->createMock(Client::class);
        $this->stringHelperMock = $this->createMock(StringHelper::class);
        $this->filmImageHelperMock = $this->createMock(FilmImageHelper::class);

        $elasticsearchServiceMock->expects(static::atLeastOnce())
            ->method('getClient')
            ->willReturn($this->elasticsearchClientMock);

        $this->filmsIndexerService = new FilmsIndexerService(
            $elasticsearchServiceMock,
            self::ELASTIC_SEARCH_INDEX_NAME,
            self::ELASTIC_SEARCH_TYPE_FILM,
            $this->stringHelperMock,
            $this->filmImageHelperMock
        );
    }

    public function testCreateMapping(): void
    {
        $expectedMapping = [
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

        $expectedIndexParams = [
            'index' => self::ELASTIC_SEARCH_INDEX_NAME . '_' . time(),
            'body' => [
                'mappings' => [
                    self::ELASTIC_SEARCH_TYPE_FILM => $expectedMapping,
                ],
            ],
        ];

        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->elasticsearchClientMock->expects(static::once())
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(static::once())
            ->method('create')
            ->with($expectedIndexParams);

        $this->filmsIndexerService->createMapping();
    }

    public function testIndex(): void
    {
        $film = new Film();
        $film->setIdFilm(1);
        $film->setTitle('Film test title');
        $film->setOriginalTitle('Film test title original');
        $film->setRating(8);
        $film->setNumRatings(5000);
        $film->setPopularityRanking(5);
        $film->setYear(2020);
        $film->setDuration(120);
        $film->setCountry('US');
        $film->setInTheatres(false);
        $film->setReleaseDate('2020-01-01');
        $film->setSynopsis('Some synopsis');
        $film->setDirectors('Director');
        $film->setActors('Actor 1, Actor 2, Actor 3');
        $film->setScreenplayers('Screenplayer 1, Screenplayer 2');
        $film->setMusicians('Musician');
        $film->setCinematographers('Cinematographer');
        $film->setTopics('Topic 1, Topic 2, Topic 3, Topic 4');
        $filmCollection = new FilmCollection(...[$film]);

        $this->stringHelperMock->expects(static::atLeastOnce())
            ->method('getSanitizedWordPermutations')
            ->withConsecutive([$film->getTitle()], [$film->getOriginalTitle()])
            ->willReturn([$film->getTitle()], [$film->getOriginalTitle()]);

        $this->filmImageHelperMock->expects(static::atLeastOnce())
            ->method('getImagePosters')
            ->with($film->getIdFilm())
            ->willReturn([]);

        $filmForIndex = [
            'idFilm' => $film->getIdFilm(),
            'suggest' => [
                'input' => array_values(
                    array_unique(
                        array_merge(
                            [$film->getTitle()],
                            [$film->getOriginalTitle()],
                        )
                    )
                ),
                'weight' => $film->getNumRatings(),
            ],
            'title' => $film->getTitle(),
            'originalTitle' => $film->getOriginalTitle(),
            'rating' => $film->getRating(),
            'numRatings' => $film->getNumRatings(),
            'popularityRanking' => $film->getPopularityRanking(),
            'year' => $film->getYear(),
            'duration' => $film->getDuration(),
            'country' => $film->getCountry(),
            'inTheatres' => $film->isInTheatres(),
            'releaseDate' => $film->getReleaseDate(),
            'directors' => explode(',', $film->getDirectors()),
            'actors' => explode(',', $film->getActors()),
            'posterImages' => [],
            'synopsis' => $film->getSynopsis() ?? '',
            'topics' => explode(',', $film->getTopics()),
            'screenplayers' => explode(',', $film->getScreenplayers()),
            'musicians' => explode(',', $film->getMusicians()),
            'cinematographers' => explode(',', $film->getCinematographers()),
        ];

        $expectedIndexParams = [
            'index' => self::ELASTIC_SEARCH_INDEX_NAME . '_' . time(),
            'type' => self::ELASTIC_SEARCH_TYPE_FILM,
            'body' => '{ "index" : { "_id" : "' . $film->getIdFilm() . '" } }' . "\n"
                . json_encode($filmForIndex, JSON_THROW_ON_ERROR) . "\n",
        ];

        $this->elasticsearchClientMock->expects(static::once())
            ->method('bulk')
            ->with($expectedIndexParams);

        $this->filmsIndexerService->index($filmCollection);
    }

    public function testDeletePreviousIndexes(): void
    {
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->elasticsearchClientMock->expects(static::atLeastOnce())
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(static::once())
            ->method('getMapping')
            ->willReturn([
                'filmaffin_test_1' => [],
                'filmaffin_test_2' => [],
            ]);

        $expectedAliasParams1 = ['index' => 'filmaffin_test_1'];
        $expectedAliasParams2 = ['index' => 'filmaffin_test_2'];

        $indicesMock->expects(static::atLeastOnce())
            ->method('delete')
            ->withConsecutive([$expectedAliasParams1], [$expectedAliasParams2]);

        $this->filmsIndexerService->deletePreviousIndexes();
    }

    public function testCreateIndexAlias(): void
    {
        $expectedAliasParams = [
            'index' => self::ELASTIC_SEARCH_INDEX_NAME . '_' . time(),
            'name' => self::ELASTIC_SEARCH_INDEX_NAME,
        ];

        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->elasticsearchClientMock->expects(static::once())
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(static::once())
            ->method('putAlias')
            ->with($expectedAliasParams);

        $this->filmsIndexerService->createIndexAlias();
    }
}
