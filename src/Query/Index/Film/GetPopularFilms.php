<?php

namespace Query\Index\Film;

use Component\Elasticsearch\NormalQuery;

class GetPopularFilms extends NormalQuery
{
    const DIC_NAME = 'Query.Index.Film.GetPopularFilms';

    /**
     * @param int $numResults
     * @param int $offset
     * @return array
     */
    public function getResult($numResults, $offset)
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
