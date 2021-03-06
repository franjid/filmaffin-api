<?php

namespace App\Infrastructure\Repository\Index\Query\Film;

use App\Infrastructure\Component\Elasticsearch\NormalQuery;

class GetFilm extends NormalQuery
{
    /**
     * The script in script_score is there to return films in the same order they were passed
     * in the $idFilmList parameter
     *
     * @param string $idFilmList
     *
     * @param bool   $includeReviews
     *
     * @return array
     */
    public function getResult(
        string $idFilmList,
        bool $includeReviews
    ): array
    {
        $numResults = count(explode(',', $idFilmList));
        $reviews = static function(bool $includeReviews) {
            return $includeReviews ? ',"proReviews", "userReviews"' : '';
        };

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
