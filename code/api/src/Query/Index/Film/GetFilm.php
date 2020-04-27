<?php

namespace App\Query\Index\Film;

use App\Component\Elasticsearch\NormalQuery;

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
        "directors",
        "actors",
        "posterImages",
        "synopsis",
        "topics",
        "screenplayers",
        "musicians",
        "cinematographers"
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
