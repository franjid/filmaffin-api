<?php

namespace App\Application\Controller;

use App\Domain\Helper\StringHelper;
use App\Infrastructure\Interfaces\FilmIndexRepositoryInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FilmsController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/films",
     *     tags={"Films"},
     *     summary="Search films by title OR team member (if title is provided, team member filter is ignored)",
     *     description="Returns a list of films that match the given criteria.",
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Films with that title. It returns 10 best suggestions",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="teamMemberType",
     *         in="query",
     *         description="[directors, actors, screenplayers]",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="teamMemberName",
     *         in="query",
     *         description="Director's name, actor's name, etc",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="(Only when filtering by team member) Sort by [year, rating]",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="numResults",
     *         in="query",
     *         description="(Only when filtering by team member) Maximum amount of results to be returned (10 by default)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=10
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="(Only when filtering by team member) Offset from the first result you want to fetch",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Returned if title parameter is not set or length is < 3"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Returned when there are no films matching the given title"
     *     )
     * )
     */
    public function searchAction(
        Request $request,
        FilmIndexRepositoryInterface $filmIndexRepository,
        StringHelper $stringHelper
    ): JsonResponse {
        $availableTeamMemberType = ['directors', 'actors', 'screenplayers'];
        $availableSort = ['year', 'rating'];

        $title = $request->query->get('title');
        $teamMemberType = $request->query->get('teamMemberType');
        $teamMemberName = $request->query->get('teamMemberName');
        $sortBy = $request->query->get('sort');

        if ($title && strlen($title) >= 3) {
            $title = $stringHelper->removeDiacritics($title);

            $films = $filmIndexRepository->searchFilms($title);
        } else {
            if ($teamMemberType !== null && !in_array($teamMemberType, $availableTeamMemberType, true)) {
                return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
            }
            if (!$teamMemberName) {
                return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
            }
            if ($sortBy !== null && !in_array($sortBy, $availableSort, true)) {
                return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
            }
            if ($sortBy === null) {
                $sortBy = 'year'; // Default sort option
            }

            $numResults = $request->query->get('numResults');
            $numResults = $numResults !== null ? (int) $numResults : 10;
            $offset = $request->query->get('offset');
            $offset = $offset !== null ? (int) $offset : 0;

            $films = $filmIndexRepository->searchFilmsByTeamMember(
                $teamMemberType,
                $teamMemberName,
                $sortBy,
                $numResults,
                $offset
            );
        }

        $response = !$films->getItems() ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_OK;

        return new JsonResponse($films->toArray(), $response);
    }

    /**
     * @OA\Get(
     *     path="/films",
     *     tags={"Films"},
     *     summary="Get films by id",
     *     description="It accepts a film id or a list (separated by commas: 1, 2, 3)",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Film ID or a list of IDs (comma-separated)",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Returned when the ids are not a list of integers",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Returned when the id does not exist",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function getFilmAction(
        string $idFilmList,
        FilmIndexRepositoryInterface $filmIndexRepository
    ): JsonResponse {
        try {
            $film = $filmIndexRepository->getFilm($idFilmList);
        } catch (\Throwable) {
            return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
        }

        $response = !$film->getItems() ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_OK;

        return new JsonResponse($film->toArray(), $response);
    }

    /**
     * @OA\Get(
     *     path="/films",
     *     tags={"Films"},
     *     summary="Get popular films",
     *     description="Retrieve a list of popular films.",
     *     @OA\Parameter(
     *         name="numResults",
     *         in="query",
     *         description="Maximum amount of results to be returned (10 by default)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=10
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
     *         description="Returned when successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Returned when the id does not exist"
     *     )
     * )
     */
    public function getPopularFilmsAction(
        Request $request,
        FilmIndexRepositoryInterface $filmIndexRepository
    ): JsonResponse {
        $numResults = $request->query->get('numResults');
        $numResults = $numResults !== null ? (int) $numResults : 10;
        $offset = $request->query->get('offset');
        $offset = $offset !== null ? (int) $offset : 0;

        $film = $filmIndexRepository->getPopularFilms($numResults, $offset);
        $response = !$film->getItems() ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;

        return new JsonResponse($film->toArray(), $response);
    }

    /**
     * @OA\Get(
     *     path="/films",
     *     tags={"Films"},
     *     summary="Get current films in theatres",
     *     description="Retrieves the list of current films in theatres.",
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort films by [releaseDate, rating, numRatings]",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Returned when the id does not exist"
     *     )
     * )
     */
    public function getFilmsInTheatresAction(
        Request $request,
        FilmIndexRepositoryInterface $filmIndexRepository
    ): JsonResponse {
        $availableSort = ['releaseDate', 'rating', 'numRatings'];

        $sortBy = $request->query->get('sort');

        if ($sortBy !== null && !in_array($sortBy, $availableSort, true)) {
            return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($sortBy === null) {
            $sortBy = 'releaseDate'; // Default sort option
        }

        $film = $filmIndexRepository->getFilmsInTheatres(50, $sortBy);
        $response = !$film->getItems() ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;

        return new JsonResponse($film->toArray(), $response);
    }

    /**
     * @OA\Get(
     *     path="/films",
     *     tags={"Films"},
     *     summary="Get new films by platform",
     *     description="Retrieves new films based on the given platform.",
     *     @OA\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function getNewFilmsInPlatformAction(
        string $platform,
        FilmIndexRepositoryInterface $filmIndexRepository
    ): JsonResponse {
        $film = $filmIndexRepository->getNewFilmsInPlatform($platform, 50);
        $response = !$film->getItems() ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;

        return new JsonResponse($film->toArray(), $response);
    }
}
