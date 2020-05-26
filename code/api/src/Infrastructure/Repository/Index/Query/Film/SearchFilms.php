<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Component\Elasticsearch\SuggestionQuery;

class SearchFilms extends SuggestionQuery
{
    public function getResult(string $title): array
    {
        $query = <<<EOT
{
    "_source": [
        "idFilm",
        "title",
        "originalTitle",
        "rating",
        "numRatings",
        "year",
        "duration",
        "country",
        "directors",
        "actors",
        "posterImages"
    ],
    "suggest": {
        "film-suggest" : {
            "prefix" : "$title",
            "completion" : {
                "field" : "suggest",
                "size" : 10
            }
        }
    }
}
EOT;
        return $this->fetchAll($query);
    }
}
