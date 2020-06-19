<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetUserReviews extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' username';
        $query .= ' , idUser';
        $query .= ' , rating';
        $query .= ' , title';
        $query .= ' , review';
        $query .= ' , spoiler';
        $query .= ' , datePublished';
        $query .= ' FROM';
        $query .= ' userReview';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY position ASC';
        $query .= ' LIMIT 10';

        return $this->fetchAll($query);
    }
}
