<?php

namespace App\Domain\Service;

use App\Domain\Entity\Collection\FilmRatedByUserCollection;
use App\Domain\Entity\Collection\FilmRatedByUserExtendedCollection;
use App\Domain\Entity\FilmRatedByUser;
use App\Domain\Entity\FilmRatedByUserExtended;
use App\Domain\Entity\UserFilmaffinity;
use App\Domain\Interfaces\UserFriendsFilmsInterface;
use App\Infrastructure\Exception\Database\UserNotFoundException;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
use App\Infrastructure\Interfaces\FilmIndexRepositoryInterface;
use App\Infrastructure\Interfaces\UserDatabaseRepositoryInterface;

class UserFriendsFilmsService implements UserFriendsFilmsInterface
{
    private UserDatabaseRepositoryInterface $userDatabaseRepository;
    private FilmDatabaseRepositoryInterface $filmDatabaseRepository;
    private FilmIndexRepositoryInterface $filmIndexRepository;

    public function __construct(
        UserDatabaseRepositoryInterface $userDatabaseRepository,
        FilmDatabaseRepositoryInterface $filmDatabaseRepository,
        FilmIndexRepositoryInterface $filmIndexRepository
    )
    {
        $this->userDatabaseRepository = $userDatabaseRepository;
        $this->filmDatabaseRepository = $filmDatabaseRepository;
        $this->filmIndexRepository = $filmIndexRepository;
    }

    /**
     * @param int $idUser
     * @param int $numResults
     * @param int $offset
     *
     * @return FilmRatedByUserExtendedCollection
     */
    public function getUserFriendsFilms(
        int $idUser,
        int $numResults,
        int $offset
    ): FilmRatedByUserExtendedCollection
    {
        try {
            $this->userDatabaseRepository->getUser($idUser);
        } catch (UserNotFoundException $e) {
            throw new \App\Domain\Exception\UserNotFoundException('User id not found: ' . $idUser);
        }

        $filmsRatedByUserFriends = $this->filmDatabaseRepository->getFilmsRatedByUserFriends(
            $idUser,
            $numResults,
            $offset
        );

        if (!$filmsRatedByUserFriends->getItems()) {
            return new FilmRatedByUserExtendedCollection();
        }

        $idFilms = array_column($filmsRatedByUserFriends->toArray(), FilmRatedByUser::FIELD_ID_FILM);
        $filmsFromIndex = $this->filmIndexRepository->getFilm(implode(', ', $idFilms));

        $filmsRatedByUserFriendsArray = array_reduce($filmsRatedByUserFriends->toArray(), static function ($result, $item) {
            $result[$item['idFilm']] = $item;
            return $result;
        });

        $filmsRatedByUserExtended = [];

        foreach ($filmsFromIndex->getItems() as $film) {
            $filmsRatedByUserExtended[] = new FilmRatedByUserExtended(
                $film,
                new UserFilmaffinity(
                    $filmsRatedByUserFriendsArray[$film->getIdFilm()][FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_USER_ID],
                    $filmsRatedByUserFriendsArray[$film->getIdFilm()][FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_USER_NAME],
                    $filmsRatedByUserFriendsArray[$film->getIdFilm()][FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_COOKIE],
                ),
                $filmsRatedByUserFriendsArray[$film->getIdFilm()][FilmRatedByUser::FIELD_USER_RATING],
                (new \DateTimeImmutable())->setTimestamp($filmsRatedByUserFriendsArray[$film->getIdFilm()][FilmRatedByUser::FIELD_DATE_RATED])
            );
        }

        return new FilmRatedByUserExtendedCollection(...$filmsRatedByUserExtended);
    }
}
