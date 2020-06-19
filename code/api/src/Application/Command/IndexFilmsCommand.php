<?php

namespace App\Application\Command;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Interfaces\FilmPopulatorInterface;
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
    private FilmPopulatorInterface $filmPopulator;

    public function __construct(
        FilmDatabaseRepositoryInterface $filmDatabaseRepository,
        FilmsIndexerInterface $filmsIndexerService,
        FilmPopulatorInterface $filmPopulator
    )
    {
        $this->filmDatabaseRepository = $filmDatabaseRepository;
        $this->filmsIndexerService = $filmsIndexerService;
        $this->filmPopulator = $filmPopulator;

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

        $progressBar = new ProgressBar($output, $this->filmDatabaseRepository->getFilmsCount());
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');

        $progressBar->start();

        $offset = 0;

        do {
            $films = $this->filmDatabaseRepository->getFilms($offset, static::MAX_FILMS_PER_ITERATION)->getItems();
            $filmsAvailable = count($films);

            if ($filmsAvailable) {
                foreach ($films as $film) {
                    $this->filmPopulator->populateFilm($film);
                }

                $this->filmsIndexerService->index(new FilmCollection(...$films));

                $progressBar->advance(static::MAX_FILMS_PER_ITERATION);
                $offset += static::MAX_FILMS_PER_ITERATION;
            }
        } while ($filmsAvailable);

        $this->filmsIndexerService->deletePreviousIndexes();
        $this->filmsIndexerService->createIndexAlias();

        return 0;
    }
}
