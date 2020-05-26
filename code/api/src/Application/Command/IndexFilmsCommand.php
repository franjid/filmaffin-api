<?php

namespace App\Application\Command;

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
            ->setDescription('Get films from DB and index them in Elasticsearch')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->filmsIndexerService->createMapping();

        $progressBar = new ProgressBar($output);
        $progressBar->start();

        $offset = 0;

        do {
            $films = $this->filmDatabaseRepository->getFilms($offset, static::MAX_FILMS_PER_ITERATION);
            $filmsAvailable = count($films);

            if ($filmsAvailable) {
                foreach ($films as $film) {
                    $idFilm = $film->getIdFilm();

                    $film->setDirectors(implode(', ', array_column($this->filmDatabaseRepository->getFilmDirectors($idFilm), 'name')));
                    $film->setActors(implode(', ', array_column($this->filmDatabaseRepository->getFilmActors($idFilm), 'name')));
                    $film->setScreenplayers(implode(', ', array_column($this->filmDatabaseRepository->getFilmScreenplayers($idFilm), 'name')));
                    $film->setMusicians(implode(', ', array_column($this->filmDatabaseRepository->getFilmMusicians($idFilm), 'name')));
                    $film->setCinematographers(implode(', ', array_column($this->filmDatabaseRepository->getFilmCinematographers($idFilm), 'name')));
                    $film->setTopics(implode(', ', array_column($this->filmDatabaseRepository->getFilmTopics($idFilm), 'name')));
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