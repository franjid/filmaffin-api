<?php

namespace Query\Index\Film;

use Component\Elasticsearch\NormalQuery;

class GetPopularFilms extends NormalQuery
{
    const DIC_NAME = 'Query.Index.Film.GetPopularFilms';

    /**
     * @return array
     */
    public function getResult()
    {
        $query = <<<EOT
{
    "_source": ["idFilm", "title", "originalTitle", "rating", "numRatings", "year", "duration", "country", "directors", "popularityRanking"],
    "sort": [{
        "popularityRanking": {
            "order": "asc"
        }
    }],
    "size": 20,
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
