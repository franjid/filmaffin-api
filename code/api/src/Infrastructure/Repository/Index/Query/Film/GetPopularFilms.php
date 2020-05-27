<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Infrastructure\Component\Elasticsearch\NormalQuery;

class GetPopularFilms extends NormalQuery
{
    public function getResult(int $numResults, int $offset): array
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
        "popularityRanking",
        "posterImages"
    ],
    "sort": [{
        "popularityRanking": {
            "order": "asc"
        }
    }],
    "size": $numResults,
    "from": $offset,
    "query": {
        "range" : {
            "popularityRanking" : {
                "gte": "1",
                "lte": "50"
            }
        }
    }
}
EOT;
        return $this->fetchAll($query);
    }
}
