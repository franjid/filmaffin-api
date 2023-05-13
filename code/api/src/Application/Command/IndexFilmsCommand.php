<?php

namespace App\Application\Command;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Interfaces\FilmPopulatorInterface;
use App\Domain\Interfaces\FilmsIndexerInterface;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IndexFilmsCommand extends Command
{
    protected static $defaultName = self::COMMAND_NAME;
    public const COMMAND_NAME = 'filmaffin:films:index';
    private const MAX_FILMS_PER_ITERATION = 100;
    private const OPTION_TIMESTAMP = 'timestamp';

    private FilmDatabaseRepositoryInterface $filmDatabaseRepository;
    private FilmsIndexerInterface $filmsIndexerService;
    private FilmPopulatorInterface $filmPopulator;

    public function __construct(
        FilmDatabaseRepositoryInterface $filmDatabaseRepository,
        FilmsIndexerInterface $filmsIndexerService,
        FilmPopulatorInterface $filmPopulator
    ) {
        $this->filmDatabaseRepository = $filmDatabaseRepository;
        $this->filmsIndexerService = $filmsIndexerService;
        $this->filmPopulator = $filmPopulator;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Get films from DB and index them in Elasticsearch')
            ->addOption(
                self::OPTION_TIMESTAMP,
                't',
                InputOption::VALUE_OPTIONAL,
                'It will index films updated after the given timestamp',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timestamp = $input->getOption(self::OPTION_TIMESTAMP);

        if ($timestamp === null) {
            $this->filmsIndexerService->createMapping();
        } else {
            $indexName = $this->filmsIndexerService->getLastIndexName();
            $this->filmsIndexerService->setCurrentIndexName($indexName);
        }

        $totalFilmsToIndex = $this->filmDatabaseRepository->getFilmsCount($timestamp);

        if (!$totalFilmsToIndex) {
            $output->writeln('Nothing to index');

            return 0;
        }

        $progressBar = new ProgressBar($output, $totalFilmsToIndex);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');

        $progressBar->start();

        $offset = 0;

        do {
            $films = $this->filmDatabaseRepository->getFilms(
                $offset,
                static::MAX_FILMS_PER_ITERATION,
                $timestamp
            )->getItems();
            $filmsAvailable = count($films);

            if ($filmsAvailable) {
                foreach ($films as $film) {
                    $this->filmPopulator->populateFilm($film);
                }

                $this->filmsIndexerService->index(new FilmCollection(...$films));

                $progressBar->advance(
                    $filmsAvailable < static::MAX_FILMS_PER_ITERATION
                        ? $filmsAvailable
                        : static::MAX_FILMS_PER_ITERATION
                );
                $offset += static::MAX_FILMS_PER_ITERATION;
            }
        } while ($filmsAvailable);

        if ($timestamp === null) {
            $this->filmsIndexerService->deletePreviousIndexes();
            $this->filmsIndexerService->createIndexAlias();
        }

        return 0;
    }
}
