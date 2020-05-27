<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Infrastructure\Component\Elasticsearch\NormalQuery;

class GetFilm extends NormalQuery
{
    public function getResult(int $idFilm): array
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
        "synopsis",
        "directors",
        "actors",
        "screenplayers",
        "musicians",
        "cinematographers"
        "topics",
        "posterImages",
    ],
    "query": {
        "function_score": {
            "boost_mode": "replace",
            "query": {
                "ids": {
                    "values": [$idFilm]
                }
            },
            "script_score" : {
                "script" : {
                  "lang": "painless",
                  "params": {
                      "ids": [$idFilm]
                  },
                  "inline": "int idsLength = params.ids.size(); int idsIndex = 0; long id = doc['idFilm'].value; for (int i = 0; i < idsLength; ++i) { if (id == params.ids[i]) { idsIndex = i * -1; } } return idsIndex;"
                }
            }
        }
    }
}
EOT;

        return $this->fetchAll($query);
    }
}
