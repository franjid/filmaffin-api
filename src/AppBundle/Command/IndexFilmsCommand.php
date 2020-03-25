<?php

namespace AppBundle\Command;

use BusinessCase\Film\FilmsIndexBusinessCaseInterface;
use Repository\Db\Film\FilmRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexFilmsCommand extends ContainerAwareCommand
{
    private const MAX_FILMS_PER_ITERATION = 100;

    protected function configure()
    {
        $this
            ->setName('filmaffin:index:films')
            ->setDescription('Get films from DB and index them in Elasticsearch')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FilmRepositoryInterface $filmRepository */
        $filmRepository = $this->getContainer()->get(FilmRepositoryInterface::DIC_NAME);

        /** @var FilmsIndexBusinessCaseInterface $filmsIndexBC */
        $filmsIndexBC = $this->getContainer()->get(FilmsIndexBusinessCaseInterface::DIC_NAME);

        $filmsIndexBC->createMapping();

        $progressBar = new ProgressBar($output);
        $progressBar->start();

        $offset = 0;

        do {
            $films = $filmRepository->getFilms($offset, static::MAX_FILMS_PER_ITERATION);
            $filmsAvailable = count($films);

            if ($filmsAvailable) {
                $idFilms = [];
                foreach ($films as $film) {
                    $idFilms[] = $film->getIdFilm();
                }

                $filmExtraInfo = $filmRepository->getFilmExtraInfo($idFilms);

                $i = 0;
                foreach ($films as $film) {
                    $film->setDirectors($filmExtraInfo[$i]->getDirectors());
                    $film->setActors($filmExtraInfo[$i]->getActors());
                    $film->setActors($filmExtraInfo[$i]->getActors());
                    $film->setScreenplayers($filmExtraInfo[$i]->getScreenplayers());
                    $film->setMusicians($filmExtraInfo[$i]->getMusicians());
                    $film->setCinematographers($filmExtraInfo[$i]->getCinematographers());
                    $film->setTopics($filmExtraInfo[$i]->getTopics());

                    $i++;
                }

                if ($filmsAvailable) {
                    $filmsIndexBC->index($films);

                    $progressBar->advance(static::MAX_FILMS_PER_ITERATION);
                    $offset += static::MAX_FILMS_PER_ITERATION;
                }
            }
        } while ($filmsAvailable);

        $filmsIndexBC->deletePreviousIndexes();
        $filmsIndexBC->createIndexAlias();
    }
}
