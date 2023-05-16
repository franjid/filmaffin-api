<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetLastRatingNotificated extends GlobalReadQuery
{
    public function getResult(int $idUser): array
    {
        $query = 'SELECT';
        $query .= '   idUserRating';
        $query .= ' FROM';
        $query .= ' userLastRatingNotificated';
        $query .= ' WHERE idUser = '.$idUser;
        $query .= ' LIMIT 1';

        return $this->fetchAll($query);
    }
}
