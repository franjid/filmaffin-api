<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Infrastructure\Component\Elasticsearch\NormalQuery;

class GetFilm extends NormalQuery
{
    /**
     * The script in script_score is there to return films in the same order they were passed
     * in the $idFilmList parameter.
     */
    public function getResult(
        string $idFilmList,
        bool $includeReviews
    ): array {
        $numResults = count(explode(',', $idFilmList));
        $reviews = static fn(bool $includeReviews) => $includeReviews ? ',"proReviews", "userReviews"' : '';

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
        "cinematographers",
        "genres",
        "topics",
        "numFrames",
        "platforms",
        "posterImages"
        {$reviews($includeReviews)}
    ],
    "size": $numResults,
    "query": {
        "function_score": {
            "boost_mode": "replace",
            "query": {
                "ids": {
                    "values": [$idFilmList]
                }
            },
            "script_score" : {
                "script" : {
                  "lang": "painless",
                  "params": {
                      "ids": [$idFilmList]
                  },
                  "source": "int idsLength = params.ids.size(); int idsIndex = 99999; long id = doc['idFilm'].value; for (int i = 0; i < idsLength; ++i) { if (id != params.ids[i]) { idsIndex = idsIndex + i; } } return idsIndex;"
                }
            }
        }
    }
}
EOT;

        return $this->fetchAll($query);
    }
}
