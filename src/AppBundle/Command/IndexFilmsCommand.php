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
    const MAX_FILMS_PER_ITERATION = 1000;

    protected function configure()
    {
        $this
            ->setName('filmaffin:index:films')
            ->setDescription('Get films from DB and index them in Elasticsearch')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return null
     */
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
                $filmsIndexBC->index($films);

                $progressBar->advance(static::MAX_FILMS_PER_ITERATION);
                $offset += static::MAX_FILMS_PER_ITERATION;
            }
        } while ($filmsAvailable);

        $filmsIndexBC->deletePreviousIndexes();
        $filmsIndexBC->createIndexAlias();
    }
}
