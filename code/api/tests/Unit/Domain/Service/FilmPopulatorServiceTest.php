<?php

namespace Tests\Unit\Domain\Service;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\Collection\ProReviewCollection;
use App\Domain\Entity\Collection\UserReviewCollection;
use App\Domain\Entity\Film;
use App\Domain\Entity\FilmAttribute;
use App\Domain\Entity\FilmParticipant;
use App\Domain\Entity\UserReview;
use App\Domain\Service\FilmPopulatorService;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FilmPopulatorServiceTest extends TestCase
{
    private FilmDatabaseRepositoryInterface $filmDatabaseRepositoryMock;

    private FilmPopulatorService $filmPopulatorService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filmDatabaseRepositoryMock = $this->createMock(FilmDatabaseRepositoryInterface::class);

        $this->filmPopulatorService = new FilmPopulatorService($this->filmDatabaseRepositoryMock);
    }

    public function testPopulateFilm(): void
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
            new FilmParticipantCollection(),
            new FilmParticipantCollection(),
            new FilmParticipantCollection(),
            new FilmParticipantCollection(),
            new FilmParticipantCollection(),
            new FilmAttributeCollection(),
            new ProReviewCollection(),
            new UserReviewCollection(),
            null
        );

        $director1 = new FilmParticipant('Director 1');
        $director2 = new FilmParticipant('Director 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmDirectors')
            ->willReturn(new FilmParticipantCollection(...[$director1, $director2]));

        $actor1 = new FilmParticipant('Actor 1');
        $actor2 = new FilmParticipant('Actor 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmActors')
            ->willReturn(new FilmParticipantCollection(...[$actor1, $actor2]));

        $screenplayer1 = new FilmParticipant('Screenplayer 1');
        $screenplayer2 = new FilmParticipant('Screenplayer 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmScreenplayers')
            ->willReturn(new FilmParticipantCollection(...[$screenplayer1, $screenplayer2]));

        $musician1 = new FilmParticipant('Musician 1');
        $musician2 = new FilmParticipant('Musician 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmMusicians')
            ->willReturn(new FilmParticipantCollection(...[$musician1, $musician2]));

        $cinematographer1 = new FilmParticipant('Cinematographer 1');
        $cinematographer2 = new FilmParticipant('Cinematographer 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmCinematographers')
            ->willReturn(new FilmParticipantCollection(...[$cinematographer1, $cinematographer2]));

        $topic1 = new FilmAttribute('Topic 1');
        $topic2 = new FilmAttribute('Topic 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmTopics')
            ->willReturn(new FilmAttributeCollection(...[$topic1, $topic2]));

        $userReview1 = new UserReview('username 1', 1, 7, 'Title 1', 'Review 1', null, (new DateTimeImmutable())->setTimestamp(12345));
        $userReview2 = new UserReview('username 2', 2, 8, 'Title 2', 'Review 2', 'Spoiler 2', (new DateTimeImmutable())->setTimestamp(123456));
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getUserReviews')
            ->willReturn(new UserReviewCollection(...[$userReview1, $userReview2]));

        $this->filmPopulatorService->populateFilm($film);

        $this->assertSame(['Director 1', 'Director 2'], $film->getDirectors()->toArray());
        $this->assertSame(['Actor 1', 'Actor 2'], $film->getActors()->toArray());
        $this->assertSame(['Screenplayer 1', 'Screenplayer 2'], $film->getScreenplayers()->toArray());
        $this->assertSame(['Musician 1', 'Musician 2'], $film->getMusicians()->toArray());
        $this->assertSame(['Cinematographer 1', 'Cinematographer 2'], $film->getCinematographers()->toArray());
        $this->assertSame(['Topic 1', 'Topic 2'], $film->getTopics()->toArray());
        $this->assertSame(
            [
                [
                    UserReview::FIELD_USERNAME => 'username 1',
                    UserReview::FIELD_ID_USER => 1,
                    UserReview::FIELD_RATING => 7,
                    UserReview::FIELD_TITLE => 'Title 1',
                    UserReview::FIELD_REVIEW => 'Review 1',
                    UserReview::FIELD_SPOILER => null,
                    UserReview::FIELD_DATE_PUBLISHED => 12345,
                ],
                [
                    UserReview::FIELD_USERNAME => 'username 2',
                    UserReview::FIELD_ID_USER => 2,
                    UserReview::FIELD_RATING => 8,
                    UserReview::FIELD_TITLE => 'Title 2',
                    UserReview::FIELD_REVIEW => 'Review 2',
                    UserReview::FIELD_SPOILER => 'Spoiler 2',
                    UserReview::FIELD_DATE_PUBLISHED => 123456,
                ],
            ],
            $film->getUserReviews()->toArray()
        );
    }
}
