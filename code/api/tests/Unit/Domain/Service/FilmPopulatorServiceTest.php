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
            new FilmAttributeCollection()
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

        $this->filmPopulatorService->populateFilm($film);

        $this->assertSame([['name' => 'Director 1'], ['name' => 'Director 2']], $film->getDirectors()->toArray());
        $this->assertSame([['name' => 'Actor 1'], ['name' => 'Actor 2']], $film->getActors()->toArray());
        $this->assertSame([['name' => 'Screenplayer 1'], ['name' => 'Screenplayer 2']], $film->getScreenplayers()->toArray());
        $this->assertSame([['name' => 'Musician 1'], ['name' => 'Musician 2']], $film->getMusicians()->toArray());
        $this->assertSame([['name' => 'Cinematographer 1'], ['name' => 'Cinematographer 2']], $film->getCinematographers()->toArray());
        $this->assertSame([['name' => 'Topic 1'], ['name' => 'Topic 2']], $film->getTopics()->toArray());
    }
}
