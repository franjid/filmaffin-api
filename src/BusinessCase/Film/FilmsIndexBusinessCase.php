<?php

namespace BusinessCase\Film;

use Component\Util\StringUtil;
use Service\Elasticsearch\ElasticsearchServiceInterface;
use Elasticsearch\Client;
use Entity\Film;
use Exception;

class FilmsIndexBusinessCase implements FilmsIndexBusinessCaseInterface
{
    private Client $elasticsearchClient;

    private string $elasticsearchIndexName;

    private string $elasticsearchTypeFilm;

    private array $indexParams;

    public function __construct(
        ElasticsearchServiceInterface $elasticsearch,
        string $elasticsearchIndexName,
        string $elasticsearchTypeFilm
    )
    {
        $this->elasticsearchClient = $elasticsearch->getClient();
        $this->elasticsearchIndexName = $elasticsearchIndexName;
        $this->elasticsearchTypeFilm = $elasticsearchTypeFilm;

        $this->indexParams = [
            'index' => $elasticsearchIndexName . '_' . time(),
        ];
    }

    public function createMapping(): void
    {
        $mapping = [
            'properties' => [
                'idFilm' => ['type' => 'long'],
                'suggest' => [
                    'type' => 'completion'
                ],
                'title' => [
                    'type' => 'text',
                    'analyzer' => 'spanish',
                ],
                'originalTitle' => [
                    'type' => 'text',
                    'analyzer' => 'english',
                ],
                'rating' => ['type' => 'float'],
                'numRatings' => ['type' => 'integer'],
                'popularityRanking' => ['type' => 'integer'],
                'year' => ['type' => 'integer'],
                'duration' => ['type' => 'integer'],
                'country' => [
                    'type' => 'keyword',
                    'index' => 'false',
                ],
                'inTheatres' => [
                    'type' => 'boolean',
                ],
                'releaseDate' => [
                    'type' => 'date',
                ],
                'directors' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'actors' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'posterImages' => [
                    'type' => 'object',
                ],
                'synopsis' => [
                    'type' => 'keyword',
                    'index' => 'false',
                ],
                'topics' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'screenplayers' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'musicians' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
                'cinematographers' => [
                    'type' => 'keyword',
                    'index' => 'true',
                ],
            ]
        ];

        $indexParams = $this->indexParams;
        $indexParams['body']['mappings'][$this->elasticsearchTypeFilm] = $mapping;
        $this->elasticsearchClient->indices()->create($indexParams);
    }

    public function index(array $films): void
    {
        $this->indexParams['type'] = $this->elasticsearchTypeFilm;
        $this->indexParams['body'] = '';

        /** @var Film $film */
        foreach ($films as $film) {
            $numRatings = $film->getNumRatings() ?: 0;

            $filmForIndex = [
                'idFilm' => $film->getIdFilm(),
                'suggest' => [
                    'input' => array_values(
                        array_unique(
                            array_merge(
                                $this->getSanitizedWordPermutations($film->getTitle()),
                                $this->getSanitizedWordPermutations($film->getOriginalTitle())
                            )
                        )
                    ),
                    'weight' => $numRatings
                    ],
                'title' => $film->getTitle(),
                'originalTitle' => $film->getOriginalTitle(),
                'rating' => $film->getRating(),
                'numRatings' => $numRatings,
                'popularityRanking' => $film->getPopularityRanking(),
                'year' => $film->getYear(),
                'duration' => $film->getDuration(),
                'country' => $film->getCountry(),
                'inTheatres' => $film->isInTheatres(),
                'releaseDate' => $film->getReleaseDate(),
                'directors' => explode(',', $film->getDirectors()),
                'actors' => explode(',', $film->getActors()),
                'posterImages' => $this->getImagePosters($film->getIdFilm()),
                'synopsis' => $film->getSynopsis() ?? '',
                'topics' => explode(',', $film->getTopics()),
                'screenplayers' => explode(',', $film->getScreenplayers()),
                'musicians' => explode(',', $film->getMusicians()),
                'cinematographers' => explode(',', $film->getCinematographers()),
            ];

            $this->indexParams['body'] .= '{ "index" : { "_id" : "' . $film->getIdFilm() . '" } }' . "\n";
            $this->indexParams['body'] .= json_encode($filmForIndex) . "\n";
        }

        try {
            $jsonError = json_last_error();
            if ($jsonError) {
                throw new Exception('Json malformed. Error code ' . $jsonError . '. Film id: ' . $film->getIdFilm());
            }
            $this->elasticsearchClient->bulk($this->indexParams);
        } catch (Exception $e) {
            print_r($this->indexParams);
            throw new Exception($e);
        }
    }

    private function getSanitizedWordPermutations($inStr): array
    {
        $inStr = StringUtil::removeDiacritics($inStr);
        $inStr = mb_ereg_replace(
            '#[[:punct:]]#', '', trim(str_replace(['(c)', '(s)'], ['', ''], mb_strtolower($inStr)))
        );

        $outArr = [];
        $tokenArr = explode(' ', $inStr);
        $numTokenArr = count($tokenArr);
        $pointer = 0;

        for ($i = 0; $i < $numTokenArr; $i++) {
            if (!empty($tokenArr[$i])) {
                $outArr[$pointer] = $tokenArr[$i];
            }
            $tokenString = $tokenArr[$i];
            $pointer++;

            for ($j = $i + 1; $j < $numTokenArr; $j++) {
                $tokenString .= ' ' . $tokenArr[$j];
                if (!empty($tokenString)) {
                    $outArr[$pointer] = $tokenString;
                }

                $pointer++;
            }
        }

        return $outArr;
    }

    public function deletePreviousIndexes(): void
    {
        $previousIndexes = $this->getPreviousIndexes();

        if (!$previousIndexes) {
            return;
        }

        $indexesNames = array_keys($previousIndexes);

        foreach ($indexesNames as $indexName) {
            if ($indexName !== $this->indexParams['index'] && substr_count($indexName, $this->elasticsearchIndexName)) {
                $deleteParams['index'] = $indexName;
                $this->elasticsearchClient->indices()->delete($deleteParams);
            }
        }
    }

    public function createIndexAlias(): void
    {
        $aliasParams['index'] = $this->indexParams['index'];
        $aliasParams['name'] = $this->elasticsearchIndexName;
        $this->elasticsearchClient->indices()->putAlias($aliasParams);
    }

    private function getPreviousIndexes(): ?array
    {
        try {
            $indexes = $this->elasticsearchClient->indices()->getMapping();
        } catch (Exception $e) {
            $indexes = null;
        }

        return $indexes;
    }

    /**
     * @param int $idFilm
     *
     * @return array
     */
    private function getImagePosters($idFilm): array
    {
        $imagePath ='/' . implode('/', str_split($idFilm, 2)) . '/';

        return [
            'small' => $imagePath . $idFilm . '-msmall.jpg',
            'medium' => $imagePath . $idFilm . '-mmed.jpg',
            'large' => $imagePath . $idFilm . '-large.jpg',
        ];
    }
}
