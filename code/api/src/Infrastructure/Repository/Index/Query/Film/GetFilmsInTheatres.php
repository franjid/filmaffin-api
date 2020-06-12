<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Infrastructure\Component\Elasticsearch\NormalQuery;

class GetFilmsInTheatres extends NormalQuery
{
    public function getResult(int $numResults, string $sortBy): array
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
    "size": $numResults,
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
