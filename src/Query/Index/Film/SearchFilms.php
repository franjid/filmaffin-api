<?php

namespace Query\Index\Film;

use Component\Elasticsearch\SuggestionQuery;

class SearchFilms extends SuggestionQuery
{
    public const DIC_NAME = 'Query.Index.Film.SearchFilms';

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
