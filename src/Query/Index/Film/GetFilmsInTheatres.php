<?php

namespace Query\Index\Film;

use Component\Elasticsearch\NormalQuery;

class GetFilmsInTheatres extends NormalQuery
{
    const DIC_NAME = 'Query.Index.Film.GetFilmsInTheatres';

    /**
     * @param string $sortBy
     * @return array
     */
    public function getResult($sortBy)
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
        "$sortBy": {
            "order": "desc"
        }
    }],
    "size": 20,
    "query": {
        "term" : {
            "inTheatres" : true
        }
    }
}
EOT;
        return $this->fetchAll($query);
    }
}
