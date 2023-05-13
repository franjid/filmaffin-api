<?php

namespace App\Application\Controller;

use App\Domain\Event\UserAddedEvent;
use App\Domain\Event\UserUpdatedEvent;
use App\Domain\Exception\UserNotFoundException;
use App\Domain\Interfaces\UserFriendsFilmsInterface;
use App\Infrastructure\Exception\Filmaffinity\InvalidUserPasswordException;
use App\Infrastructure\Interfaces\FilmaffinityRepositoryInterface;
use App\Infrastructure\Interfaces\UserDatabaseRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class UsersController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Try to log in a user into Filmaffinity",
     *     description="User/password must be provided",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="user",
     *                     type="string",
     *                     description="Filmaffinity user",
     *                     example="user@example.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Filmaffinity password",
     *                     example="password123"
     *                 ),
     *                 @OA\Property(
     *                     property="appNotificationsToken",
     *                     type="string",
     *                     description="Notification token generated by Filmaffin App",
     *                     example="token123",
     *                     nullable=true
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="If user credentials were correct"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="If no user and password are provided"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized"
     *     )
     * )
     */
    public function loginFilmaffinityAction(
        Request $request,
        FilmaffinityRepositoryInterface $filmaffinity,
        UserDatabaseRepositoryInterface $userDatabaseRepository,
        MessageBusInterface $bus
    ): JsonResponse {
        $user = $request->query->get('user');
        $password = $request->query->get('password');
        $appNotificationsToken = $request->query->get('appNotificationsToken');

        if (!$user || !$password) {
            return new JsonResponse(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $userFilmaffinity = $filmaffinity->loginUser($user, $password);
        } catch (InvalidUserPasswordException) {
            return new JsonResponse(null, JsonResponse::HTTP_UNAUTHORIZED);
        }

        try {
            $userDatabaseRepository->saveUser(
                $userFilmaffinity->getUserId(),
                $userFilmaffinity->getUserName(),
                $userFilmaffinity->getCookie(),
                $appNotificationsToken
            );

            $bus->dispatch(new UserAddedEvent($userFilmaffinity->getUserId(), $userFilmaffinity->getCookie()));
        } catch (UniqueConstraintViolationException) {
            $userDatabaseRepository->updateUser(
                $userFilmaffinity->getUserId(),
                $userFilmaffinity->getUserName(),
                $userFilmaffinity->getCookie(),
                $appNotificationsToken
            );

            $bus->dispatch(new UserUpdatedEvent($userFilmaffinity->getUserId(), $userFilmaffinity->getCookie()));
        }

        return new JsonResponse($userFilmaffinity->toArray(), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/users/{userId}/last-rated-films",
     *     tags={"Users"},
     *     summary="Get last films rated by userId friends",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="The ID of the user whose friends' last-rated films are to be fetched",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="numResults",
     *         in="query",
     *         description="Maximum amount of results to be returned (10 by default. 50 max.)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset from the first result you want to fetch",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="No results"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="If no userId is provided"
     *     )
     * )
     */
    public function userFriendsFilms(
        $idUser,
        Request $request,
        UserFriendsFilmsInterface $userFriendsFilmsService
    ): JsonResponse {
        if (!is_numeric($idUser)) {
            return new JsonResponse(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        $numResults = $request->query->get('numResults');

        if (!$numResults) {
            $numResults = 10;
        } else {
            $numResults = $numResults > 50 ? 50 : $numResults;
        }

        $offset = $request->query->get('offset');
        $offset = $offset !== null ? (int) $offset : 0;

        try {
            $filmsRatedByUserFriends = $userFriendsFilmsService->getUserFriendsFilms($idUser, $numResults, $offset);
        } catch (UserNotFoundException) {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }

        if (!$filmsRatedByUserFriends->getItems()) {
            return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse($filmsRatedByUserFriends->toArray(), JsonResponse::HTTP_OK);
    }
}
