<?php

namespace App\Application\Command;

use App\Domain\Entity\FilmAttribute;
use App\Domain\Entity\FilmParticipant;
use App\Domain\Interfaces\FilmsIndexerInterface;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexFilmsCommand extends Command
{
    private const MAX_FILMS_PER_ITERATION = 100;

    private FilmDatabaseRepositoryInterface $filmDatabaseRepository;
    private FilmsIndexerInterface $filmsIndexerService;

    public function __construct(
        FilmDatabaseRepositoryInterface $filmDatabaseRepository,
        FilmsIndexerInterface $filmsIndexerService
    )
    {
        $this->filmDatabaseRepository = $filmDatabaseRepository;
        $this->filmsIndexerService = $filmsIndexerService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('filmaffin:index:films')
            ->setDescription('Get films from DB and index them in Elasticsearch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->filmsIndexerService->createMapping();

        $progressBar = new ProgressBar($output);
        $progressBar->start();

        $offset = 0;

        do {
            $films = $this->filmDatabaseRepository->getFilms($offset, static::MAX_FILMS_PER_ITERATION)->getItems();
            $filmsAvailable = count($films);

            if ($filmsAvailable) {
                foreach ($films as $film) {
                    $idFilm = $film->getIdFilm();

                    $directors = $this->filmDatabaseRepository->getFilmDirectors($idFilm)->toArray();
                    $film->setDirectors(implode(', ', array_column($directors, FilmParticipant::FIELD_NAME)));

                    $actors = $this->filmDatabaseRepository->getFilmActors($idFilm)->toArray();
                    $film->setActors(implode(', ', array_column($actors, FilmParticipant::FIELD_NAME)));

                    $screenplayers = $this->filmDatabaseRepository->getFilmScreenplayers($idFilm)->toArray();
                    $film->setScreenplayers(implode(', ', array_column($screenplayers, FilmParticipant::FIELD_NAME)));

                    $musicians = $this->filmDatabaseRepository->getFilmMusicians($idFilm)->toArray();
                    $film->setMusicians(implode(', ', array_column($musicians, FilmParticipant::FIELD_NAME)));

                    $cinematographers = $this->filmDatabaseRepository->getFilmCinematographers($idFilm)->toArray();
                    $film->setCinematographers(implode(', ', array_column($cinematographers, FilmParticipant::FIELD_NAME)));

                    $topics = $this->filmDatabaseRepository->getFilmTopics($idFilm)->toArray();
                    $film->setTopics(implode(', ', array_column($topics, FilmAttribute::FIELD_NAME)));
                }

                $this->filmsIndexerService->index($films);

                $progressBar->advance(static::MAX_FILMS_PER_ITERATION);
                $offset += static::MAX_FILMS_PER_ITERATION;
            }
        } while ($filmsAvailable);

        $this->filmsIndexerService->deletePreviousIndexes();
        $this->filmsIndexerService->createIndexAlias();

        return 0;
    }
}
