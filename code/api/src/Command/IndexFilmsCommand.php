<?php

namespace App\Command;

use App\BusinessCase\Film\FilmsIndexBusinessCaseInterface;
use App\Repository\Db\Film\FilmRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexFilmsCommand extends Command
{
    private const MAX_FILMS_PER_ITERATION = 100;

    private FilmRepositoryInterface $filmRepository;
    private FilmsIndexBusinessCaseInterface $filmsIndexBC;

    public function __construct(
        FilmRepositoryInterface $filmRepository,
        FilmsIndexBusinessCaseInterface $filmsIndexBC
    )
    {
        $this->filmRepository = $filmRepository;
        $this->filmsIndexBC = $filmsIndexBC;

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
        $this->filmsIndexBC->createMapping();

        $progressBar = new ProgressBar($output);
        $progressBar->start();

        $offset = 0;

        do {
            $films = $this->filmRepository->getFilms($offset, static::MAX_FILMS_PER_ITERATION);
            $filmsAvailable = count($films);

            if ($filmsAvailable) {
                foreach ($films as $film) {
                    $idFilm = $film->getIdFilm();

                    $film->setDirectors(implode(', ', array_column($this->filmRepository->getFilmDirectors($idFilm), 'name')));
                    $film->setActors(implode(', ', array_column($this->filmRepository->getFilmActors($idFilm), 'name')));
                    $film->setScreenplayers(implode(', ', array_column($this->filmRepository->getFilmScreenplayers($idFilm), 'name')));
                    $film->setMusicians(implode(', ', array_column($this->filmRepository->getFilmMusicians($idFilm), 'name')));
                    $film->setCinematographers(implode(', ', array_column($this->filmRepository->getFilmCinematographers($idFilm), 'name')));
                    $film->setTopics(implode(', ', array_column($this->filmRepository->getFilmTopics($idFilm), 'name')));
                }

                $this->filmsIndexBC->index($films);

                $progressBar->advance(static::MAX_FILMS_PER_ITERATION);
                $offset += static::MAX_FILMS_PER_ITERATION;
            }
        } while ($filmsAvailable);

        $this->filmsIndexBC->deletePreviousIndexes();
        $this->filmsIndexBC->createIndexAlias();

        return 0;
    }
}
