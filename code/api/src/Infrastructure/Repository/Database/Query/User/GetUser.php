<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Domain\Entity\UserFilmaffinity;
use App\Infrastructure\Component\Db\GlobalReadQuery;
use App\Infrastructure\Exception\Database\UserNotFoundException;
use Doctrine\DBAL\DBALException;

class GetUser extends GlobalReadQuery
{
    /**
     * @param int $userIdFilmaffinity
     *
     * @return UserFilmaffinity
     * @throws UserNotFoundException
     * @throws DBALException
     */
    public function getResult(int $userIdFilmaffinity): UserFilmaffinity
    {
        $query = 'SELECT';
        $query .= '   idUser';
        $query .= ' , name';
        $query .= ' , cookieFilmaffinity';
        $query .= ' FROM';
        $query .= ' user';
        $query .= ' WHERE idUser = ' . $userIdFilmaffinity;

        $result = $this->fetchAssoc($query);

        if (!$result) {
            throw new UserNotFoundException('User id not found: ' . $userIdFilmaffinity);
        }

        return new UserFilmaffinity(
            $result['idUser'],
            $result['name'],
            $result['cookieFilmaffinity'] !== '' ? $result['cookieFilmaffinity'] : null
        );
    }
}
