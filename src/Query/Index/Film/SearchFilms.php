<?php

namespace Query\Index\Film;

use Component\Elasticsearch\SuggestionQuery;

class SearchFilms extends SuggestionQuery
{
    const DIC_NAME = 'Query.Index.Film.SearchFilms';

    /**
     * @param string $title
     * @return array
     */
    public function getResult($title)
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
        "actors"
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
