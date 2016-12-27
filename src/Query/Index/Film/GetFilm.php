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
    "_source": ["idFilm", "title", "originalTitle", "numRatings"],
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
