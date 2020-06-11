<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmRatedByUserCollection;
use App\Domain\Entity\FilmRatedByUser;
use App\Domain\Entity\UserFilmaffinity;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmsRatedByUserFriends extends GlobalReadQuery
{
    public function getResult(
        int $idUser,
        int $numResults,
        int $offset
    ): FilmRatedByUserCollection
    {
        $query = 'SELECT';
        $query .= '   f.idFilm';
        $query .= ' , u.idUser';
        $query .= ' , u.name';
        $query .= ' , ur.rating';
        $query .= ' , ur.dateRated';
        $query .= ' FROM';
        $query .= ' userRating ur';
        $query .= ' JOIN film f USING(idFilm)';
        $query .= ' JOIN userFriendship uf ON uf.idUserTarget = ur.idUser';
        $query .= ' JOIN user u ON u.idUser = uf.idUserTarget';
        $query .= ' WHERE uf.idUserSource = ' . $idUser;
        $query .= ' ORDER BY ur.dateRated DESC, ur.position ASC';
        $query .= ' LIMIT ' . $offset . ', ' . $numResults;

        $results = $this->fetchAll($query);

        if (!$results) {
            return new FilmRatedByUserCollection();
        }

        $filmsRatedByUser = [];

        foreach ($results as $result) {
            $filmsRatedByUser[] = new FilmRatedByUser(
                $result['idFilm'],
                new UserFilmaffinity(
                    $result['idUser'],
                    $result['name'],
                    null
                ),
                $result['rating'],
                (new \DateTimeImmutable())->setTimestamp($result['dateRated'])
            );
        }

        return new FilmRatedByUserCollection(...$filmsRatedByUser);
    }
}
