<?php

namespace App\Application\Command;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Film;
use App\Domain\Interfaces\FilmPopulatorInterface;
use App\Domain\Interfaces\FilmsIndexerInterface;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
use App\Infrastructure\Interfaces\FilmIndexRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexFrequentlyUpdatedFilmsCommand extends Command
{
    protected static $defaultName = 'filmaffin:films:index:frequently-updated';

    public function __construct(
        private readonly FilmDatabaseRepositoryInterface $filmDatabaseRepository,
        private readonly FilmIndexRepositoryInterface $filmIndexRepository,
        private readonly FilmsIndexerInterface $filmsIndexerService,
        private readonly FilmPopulatorInterface $filmPopulator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Index frequently updated films in Elasticsearch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $indexName = $this->filmsIndexerService->getLastIndexName();
        $this->filmsIndexerService->setCurrentIndexName($indexName);

        $this->reindexCurrentFrequentFilms();

        $films = $this->filmDatabaseRepository->getFrequentlyUpdatedFilms();
        $this->indexFilms($films);

        return 0;
    }

    /**
     * We need to reindex "current" frequent films to be sure if they continue to be hot (popular/in theatres).
     *
     * One film could have been in theatres, but in a new crawling it is not anymore,
     * so we have to check it and reindex (in this case `inTheatres` will be false)
     */
    private function reindexCurrentFrequentFilms(): void
    {
        $filmsInTheatres = $this->filmIndexRepository->getFilmsInTheatres(100, 'numRatings')->toArray();
        $idFilmsInTheatres = array_column($filmsInTheatres, Film::FIELD_ID_FILM);
        if ($idFilmsInTheatres) {
            $films = $this->filmDatabaseRepository->getFilmsById($idFilmsInTheatres);
            $this->indexFilms($films);
        }

        $popularFilms = $this->filmIndexRepository->getPopularFilms(100, 0)->toArray();
        $idFilmsPopular = array_column($popularFilms, Film::FIELD_ID_FILM);
        if ($idFilmsPopular) {
            $films = $this->filmDatabaseRepository->getFilmsById($idFilmsPopular);
            $this->indexFilms($films);
        }
    }

    private function indexFilms(FilmCollection $films): void
    {
        $filmsItems = $films->getItems();

        if (!count($filmsItems)) {
            return;
        }

        foreach ($filmsItems as $film) {
            $this->filmPopulator->populateFilm($film);
        }

        $this->filmsIndexerService->index(new FilmCollection(...$filmsItems));
    }
}
