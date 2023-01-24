<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Infrastructure\Component\Elasticsearch\NormalQuery;

class GetNewFilmsInPlatform extends NormalQuery
{
    public function getResult(string $platform, int $numResults): array
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
        "releaseDate": {
            "order": "desc"
        }
    }],
    "size": $numResults,
    "query": {
        "term" : {
            "platform" : "$platform"
        }
    }
}
EOT;

        return $this->fetchAll($query);
    }
}
