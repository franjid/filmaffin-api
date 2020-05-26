<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Component\Elasticsearch\NormalQuery;

class GetFilmsInTheatres extends NormalQuery
{
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
