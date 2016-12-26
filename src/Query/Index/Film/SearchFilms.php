<?php

namespace Query\Index\Film;

use Component\Elasticsearch\Query;

class SearchFilms extends Query
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
    "_source": ["idFilm", "title", "originalTitle", "numRatings"],
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
