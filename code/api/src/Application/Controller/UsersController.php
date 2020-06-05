<?php

namespace App\Application\Controller;

use App\Infrastructure\Interfaces\FilmaffinityRepositoryInterface;
use App\Infrastructure\Interfaces\UserDatabaseRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     *
     * @return JsonResponse
     */
    public function loginFilmaffinityAction(
        Request $request,
        FilmaffinityRepositoryInterface $filmaffinity,
        UserDatabaseRepositoryInterface $userDatabaseRepository
    ): JsonResponse
    {
        $user = $request->query->get('user');
        $password = $request->query->get('password');

        if (!$user || !$password) {
            return new JsonResponse(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        $userFilmaffinity = $filmaffinity->loginUser($user, $password);

        try {
            $userId = $userDatabaseRepository->saveUser($userFilmaffinity->getUserId(), $userFilmaffinity->getCookie());
        } catch (UniqueConstraintViolationException $e) {
            /**
             * Do nothing for now. Maybe we could update the cookie in some future use case
             */
        }

        return new JsonResponse($userFilmaffinity->toArray(), JsonResponse::HTTP_OK);
    }
}
