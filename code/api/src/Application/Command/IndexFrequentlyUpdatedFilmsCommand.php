<?php

namespace App\Application\Command;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Interfaces\FilmPopulatorInterface;
use App\Domain\Interfaces\FilmsIndexerInterface;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexFrequentlyUpdatedFilmsCommand extends Command
{
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
            ->setName('filmaffin:index:films:frequently_updated')
            ->setDescription('Index frequently updated films in Elasticsearch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $indexName = $this->filmsIndexerService->getLastIndexName();
        $this->filmsIndexerService->setCurrentIndexName($indexName);

        $films = $this->filmDatabaseRepository->getFrequentlyUpdatedFilms()->getItems();
        $filmsAvailable = count($films);

        if ($filmsAvailable) {
            foreach ($films as $film) {
                $this->filmPopulator->populateFilm($film);
            }

            $this->filmsIndexerService->index(new FilmCollection(...$films));
        }

        return 0;
    }
}
