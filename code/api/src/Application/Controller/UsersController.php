<?php

namespace App\Application\Controller;

use App\Domain\Event\UserAddedEvent;
use App\Domain\Exception\UserNotFoundException;
use App\Domain\Interfaces\UserFriendsFilmsInterface;
use App\Infrastructure\Interfaces\FilmaffinityRepositoryInterface;
use App\Infrastructure\Interfaces\UserDatabaseRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class UsersController extends AbstractController
{
    /**
     * @Operation(
     *     tags={"Users"},
     *     summary="Try to log in a user into Filmaffinity",
     *     description="User/password must be provided",
     *     @SWG\Parameter(
     *         name="user",
     *         in="query",
     *         description="Filmaffinity user",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="query",
     *         description="Filmaffinity password",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="If user credentials were correct"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="If no user and password are provided"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized"
     *     )
     * )
     *
     * @param Request                         $request
     * @param FilmaffinityRepositoryInterface $filmaffinity
     * @param UserDatabaseRepositoryInterface $userDatabaseRepository
     * @param MessageBusInterface             $bus
     *
     * @return JsonResponse
     */
    public function loginFilmaffinityAction(
        Request $request,
        FilmaffinityRepositoryInterface $filmaffinity,
        UserDatabaseRepositoryInterface $userDatabaseRepository,
        MessageBusInterface $bus
    ): JsonResponse
    {
        $user = $request->query->get('user');
        $password = $request->query->get('password');

        if (!$user || !$password) {
            return new JsonResponse(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        $userFilmaffinity = $filmaffinity->loginUser($user, $password);

        try {
            $userDatabaseRepository->saveUser(
                $userFilmaffinity->getUserId(),
                $userFilmaffinity->getUserName(),
                $userFilmaffinity->getCookie()
            );
        } catch (UniqueConstraintViolationException $e) {
            /**
             * Do nothing for now. Maybe we could update the cookie in some future use case
             * And instead of dispatching UserAddedEvent, we could dispatch UserUpdatedEvent (to resync friends, etc)
             */
        }

        /**
         * This should be in the "try" (so only the first time we save the user we dispatch this event)
         * Otherwise everytime we hit this endpoint we will send the event
         *
         * Left here for dev/debug purposes
         * @TODO: Move dispatch of event to try (after saving user)
         */
        $bus->dispatch(new UserAddedEvent($userFilmaffinity->getUserId(), $userFilmaffinity->getCookie()));

        return new JsonResponse($userFilmaffinity->toArray(), JsonResponse::HTTP_OK);
    }

    /**
     * @Operation(
     *     tags={"Users"},
     *     summary="Get last films rated by userId friends",
     *     @SWG\Parameter(
     *         name="numResults",
     *         in="query",
     *         description="Maximum amount of results to be returned (10 by default. 50 max.)",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset from the first result you want to fetch",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description=""
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="No results"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="If no userId is provided"
     *     )
     * )
     *
     * @param                           $idUser
     *
     * @param Request                   $request
     * @param UserFriendsFilmsInterface $userFriendsFilmsService
     *
     * @return JsonResponse
     */
    public function userFriendsFilms(
        $idUser,
        Request $request,
        UserFriendsFilmsInterface $userFriendsFilmsService
    ): JsonResponse
    {
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
        } catch (UserNotFoundException $e) {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }

        if (!$filmsRatedByUserFriends->getItems()) {
            return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse($filmsRatedByUserFriends->toArray(), JsonResponse::HTTP_OK);
    }
}
