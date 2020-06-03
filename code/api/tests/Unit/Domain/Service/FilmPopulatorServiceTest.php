<?php

namespace Tests\Unit\Domain\Service;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\Film;
use App\Domain\Entity\FilmAttribute;
use App\Domain\Entity\FilmParticipant;
use App\Domain\Service\FilmPopulatorService;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
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

        $director1 = new FilmParticipant();
        $director1->setName('Director 1');
        $director2 = new FilmParticipant();
        $director2->setName('Director 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmDirectors')
            ->willReturn(new FilmParticipantCollection(...[$director1, $director2]));

        $actor1 = new FilmParticipant();
        $actor1->setName('Actor 1');
        $actor2 = new FilmParticipant();
        $actor2->setName('Actor 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmActors')
            ->willReturn(new FilmParticipantCollection(...[$actor1, $actor2]));

        $screenplayer1 = new FilmParticipant();
        $screenplayer1->setName('Screenplayer 1');
        $screenplayer2 = new FilmParticipant();
        $screenplayer2->setName('Screenplayer 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmScreenplayers')
            ->willReturn(new FilmParticipantCollection(...[$screenplayer1, $screenplayer2]));

        $musician1 = new FilmParticipant();
        $musician1->setName('Musician 1');
        $musician2 = new FilmParticipant();
        $musician2->setName('Musician 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmMusicians')
            ->willReturn(new FilmParticipantCollection(...[$musician1, $musician2]));

        $cinematographer1 = new FilmParticipant();
        $cinematographer1->setName('Cinematographer 1');
        $cinematographer2 = new FilmParticipant();
        $cinematographer2->setName('Cinematographer 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmCinematographers')
            ->willReturn(new FilmParticipantCollection(...[$cinematographer1, $cinematographer2]));

        $topic1 = new FilmAttribute();
        $topic1->setName('Topic 1');
        $topic2 = new FilmAttribute();
        $topic2->setName('Topic 2');
        $this->filmDatabaseRepositoryMock->expects(static::once())
            ->method('getFilmTopics')
            ->willReturn(new FilmAttributeCollection(...[$topic1, $topic2]));

        $this->filmPopulatorService->populateFilm($film);

        $this->assertSame('Director 1, Director 2', $film->getDirectors());
        $this->assertSame('Actor 1, Actor 2', $film->getActors());
        $this->assertSame('Screenplayer 1, Screenplayer 2', $film->getScreenplayers());
        $this->assertSame('Musician 1, Musician 2', $film->getMusicians());
        $this->assertSame('Cinematographer 1, Cinematographer 2', $film->getCinematographers());
        $this->assertSame('Topic 1, Topic 2', $film->getTopics());
    }
}
