<?php

namespace Query\Index\Film;

use Component\Elasticsearch\NormalQuery;

class GetFilm extends NormalQuery
{
    const DIC_NAME = 'Query.Index.Film.GetFilm';

    /**
     * @param int $idFilm
     * @return array
     */
    public function getResult($idFilm)
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
        "ids" : {
            "values" : [$idFilm]
        }
    }
}
EOT;
        return $this->fetchAll($query);
    }
}
