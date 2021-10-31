<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Infrastructure\Component\Elasticsearch\NormalQuery;

class SearchFilmsByTeamMember extends NormalQuery
{
    public function getResult(
        string $teamMemberType,
        string $teamMemberName,
        string $sortBy,
        int $numResults,
        int $offset
    ): array
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
    "sort": [{
        "$sortBy": {
            "order": "desc"
        }
    }],
    "size": $numResults,
    "from": $offset,
    "query": {
        "match": {
          "$teamMemberType": {
            "query": "$teamMemberName"
          }
        }
    }
}
EOT;

        return $this->fetchAll($query);
    }
}
