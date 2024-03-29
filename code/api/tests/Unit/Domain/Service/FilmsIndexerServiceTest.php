<?php

namespace Tests\Unit\Domain\Service;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Collection\FilmFramesCollection;
use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\Collection\ProReviewCollection;
use App\Domain\Entity\Collection\UserReviewCollection;
use App\Domain\Entity\Film;
use App\Domain\Entity\FilmAttribute;
use App\Domain\Entity\FilmParticipant;
use App\Domain\Entity\ProReview;
use App\Domain\Entity\UserReview;
use App\Domain\Exception\IndexInconsistencyException;
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
                'numFrames' => [
                    'type' => 'integer',
                ],
            ],
        ];

        $expectedIndexParams = [
            'index' => self::ELASTIC_SEARCH_INDEX_NAME.'_'.time(),
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
        $film = new Film(
            1,
            'Film test title',
            'Film test title original',
            8,
            5000,
            5,
            2020,
            120,
            'US',
            false,
            '2020-01-01',
            'Some synopsis',
            new FilmParticipantCollection(...[new FilmParticipant('Director')]),
            new FilmParticipantCollection(...[new FilmParticipant('Actor 1'), new FilmParticipant('Actor 2'), new FilmParticipant('Actor 3')]),
            new FilmParticipantCollection(...[new FilmParticipant('Screenplayer 1'), new FilmParticipant('Screenplayer 2')]),
            new FilmParticipantCollection(...[new FilmParticipant('Musician')]),
            new FilmParticipantCollection(...[new FilmParticipant('Cinematographer')]),
            new FilmAttributeCollection(...[new FilmAttribute('Genre 1'), new FilmAttribute('Genre 2')]),
            new FilmAttributeCollection(...[new FilmAttribute('Topic 1'), new FilmAttribute('Topic 2'), new FilmAttribute('Topic 3')]),
            new ProReviewCollection(...[new ProReview('Author', 'Review', 'positive')]),
            new UserReviewCollection(...[new UserReview('username', 123, 10, 'Title', 'User Review', null, new \DateTimeImmutable())]),
            null,
            0,
            new FilmFramesCollection()
        );
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
                        [$film->getTitle(), $film->getOriginalTitle()]
                    )
                ),
                'weight' => $film->getNumRatings(),
            ],
            'title' => $film->getTitle(),
            'originalTitle' => $film->getOriginalTitle(),
            'rating' => $film->getRating(),
            'numRatings' => $film->getNumRatings(),
            'numFrames' => $film->getNumFrames(),
            'popularityRanking' => $film->getPopularityRanking(),
            'year' => $film->getYear(),
            'duration' => $film->getDuration(),
            'country' => $film->getCountry(),
            'inTheatres' => $film->isInTheatres(),
            'releaseDate' => $film->getReleaseDate(),
            'directors' => $film->getDirectors()->toArray(),
            'actors' => $film->getActors()->toArray(),
            'posterImages' => [],
            'synopsis' => $film->getSynopsis() ?? '',
            'genres' => $film->getGenres()->toArray(),
            'topics' => $film->getTopics()->toArray(),
            'screenplayers' => $film->getScreenplayers()->toArray(),
            'musicians' => $film->getMusicians()->toArray(),
            'cinematographers' => $film->getCinematographers()->toArray(),
            'proReviews' => $film->getProReviews()->toArray(),
            'userReviews' => $film->getUserReviews()->toArray(),
        ];

        $expectedIndexParams = [
            'index' => self::ELASTIC_SEARCH_INDEX_NAME.'_'.time(),
            'type' => self::ELASTIC_SEARCH_TYPE_FILM,
            'body' => '{ "index" : { "_id" : "'.$film->getIdFilm().'" } }'."\n"
                .json_encode($filmForIndex, JSON_THROW_ON_ERROR)."\n",
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
            'index' => self::ELASTIC_SEARCH_INDEX_NAME.'_'.time(),
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

    public function testGetLastIndexNameWithMoreThanOneIndex(): void
    {
        $this->expectException(IndexInconsistencyException::class);

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

        $this->filmsIndexerService->getLastIndexName();
    }

    public function testGetLastIndexName(): void
    {
        $expectedIndexName = 'filmaffin_test_1';
        $indicesMock = $this->createMock(IndicesNamespace::class);

        $this->elasticsearchClientMock->expects(static::atLeastOnce())
            ->method('indices')
            ->willReturn($indicesMock);

        $indicesMock->expects(static::once())
            ->method('getMapping')
            ->willReturn([
                $expectedIndexName => [],
            ]);

        $this->assertSame($expectedIndexName, $this->filmsIndexerService->getLastIndexName());
    }
}
