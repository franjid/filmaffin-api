<?php

namespace Query\Index\Film;

use Component\Elasticsearch\Query;

class SearchFilms extends Query
{
    const DIC_NAME = 'Query.Index.Film.SearchFilms';

    /**
     * @param string $title
     * @return array
     */
    public function getResult($title)
    {
        $query = <<<EOT
{
    "query": {
        "function_score": {
            "query": {
                "multi_match": {
                    "query":    "$title",
              "fields": ["title", "originalTitle"]
            }
          },
          "field_value_factor": {
                "field": "numRatings"
          }
        }
    }
}
EOT;
        return $this->fetchAll($query);
    }
}
