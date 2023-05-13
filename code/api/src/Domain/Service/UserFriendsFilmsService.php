<?php

namespace App\Domain\Service;

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
    ) {
        $this->userDatabaseRepository = $userDatabaseRepository;
        $this->filmDatabaseRepository = $filmDatabaseRepository;
        $this->filmIndexRepository = $filmIndexRepository;
    }

    public function getUserFriendsFilms(
        int $idUser,
        int $numResults,
        int $offset
    ): FilmRatedByUserExtendedCollection {
        try {
            $this->userDatabaseRepository->getUser($idUser);
        } catch (UserNotFoundException $e) {
            throw new \App\Domain\Exception\UserNotFoundException('User id not found: '.$idUser);
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
        $filmsFromIndex = $this->filmIndexRepository->getFilm(implode(', ', $idFilms), false);

        $filmsRatedByUserFriendsArray = array_reduce($filmsRatedByUserFriends->toArray(), static function ($result, $item) {
            $result[$item['idFilm']][] = $item;

            return $result;
        });

        $filmsRatedByUserExtendedRaw = [];

        foreach ($filmsFromIndex->getItems() as $film) {
            foreach ($filmsRatedByUserFriendsArray[$film->getIdFilm()] as $userRating) {
                $filmsRatedByUserExtendedRaw[] = [
                    FilmRatedByUserExtended::FIELD_FILM => $film,
                    FilmRatedByUserExtended::FIELD_USER => [
                        UserFilmaffinity::FIELD_USER_ID => $userRating[FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_USER_ID],
                        UserFilmaffinity::FIELD_USER_NAME => $userRating[FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_USER_NAME],
                        UserFilmaffinity::FIELD_COOKIE => $userRating[FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_COOKIE],
                    ],
                    FilmRatedByUserExtended::FIELD_USER_RATING => $userRating[FilmRatedByUser::FIELD_USER_RATING],
                    FilmRatedByUserExtended::FIELD_DATE_RATED => (new \DateTimeImmutable())->setTimestamp($userRating[FilmRatedByUser::FIELD_DATE_RATED]),
                    'dateRatedTimestamp' => $userRating[FilmRatedByUser::FIELD_DATE_RATED],
                    'idUserRating' => $userRating[FilmRatedByUser::FIELD_ID_USER_RATING],
                ];
            }
        }

        usort($filmsRatedByUserExtendedRaw, static function ($a, $b) {
            return $a['dateRatedTimestamp'] < $b['dateRatedTimestamp'];
        });

        $filmsRatedByUserExtended = [];

        foreach ($filmsRatedByUserExtendedRaw as $filmRated) {
            $filmsRatedByUserExtended[] = new FilmRatedByUserExtended(
                $filmRated['film'],
                new UserFilmaffinity(
                    $filmRated[FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_USER_ID],
                    $filmRated[FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_USER_NAME],
                    $filmRated[FilmRatedByUser::FIELD_USER][UserFilmaffinity::FIELD_COOKIE],
                ),
                $filmRated[FilmRatedByUser::FIELD_USER_RATING],
                $filmRated[FilmRatedByUser::FIELD_DATE_RATED]
            );
        }

        return new FilmRatedByUserExtendedCollection(...$filmsRatedByUserExtended);
    }
}
