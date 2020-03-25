<?php

namespace Query\Index\Film;

use Component\Elasticsearch\NormalQuery;

class GetFilmsInTheatres extends NormalQuery
{
    public const DIC_NAME = 'Query.Index.Film.GetFilmsInTheatres';

    public function getResult(string $sortBy): array
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
        "releaseDate",
        "posterImages"
    ],
    "sort": [{
        "$sortBy": {
            "order": "desc"
        }
    }],
    "size": 20,
    "query": {
        "term" : {
            "inTheatres" : true
        }
    }
}
EOT;
        return $this->fetchAll($query);
    }
}