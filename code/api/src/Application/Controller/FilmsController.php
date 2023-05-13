<?php

namespace App\Application\Controller;

use App\Domain\Helper\StringHelper;
use App\Infrastructure\Interfaces\FilmIndexRepositoryInterface;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FilmsController extends AbstractController
{
    /**
     * @Operation(
     *     tags={"Films"},
     *     summary="Search films by title OR team member (if title is provided, team member filter is ignored)",
     *
     *     @SWG\Parameter(
     *         name="title",
     *         in="query",
     *         description="Films with that title. It returns 10 best suggestions",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="teamMemberType",
     *         in="query",
     *         description="[directors, actors, screenplayers]",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="teamMemberName",
     *         in="query",
     *         description="Director's name, actor's name, etc",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="sort",
     *         in="query",
     *         description="(Only when filtering by team member) Sort by [year, rating]",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="numResults",
     *         in="query",
     *         description="(Only when filtering by team member) Maximum amount of results to be returned (10 by default)",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="offset",
     *         in="query",
     *         description="(Only when filtering by team member) Offset from the first result you want to fetch",
     *         required=false,
     *         type="integer"
     *     ),
     *
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned if title parameter is not set or lenght is < 3"
     *     ),
     *     @SWG\Response(
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
     * @Operation(
     *     tags={"Films"},
     *     summary="Get films by id",
     *     description="It accepts a film id or a list (separated by commas: 1, 2, 3)",
     *
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when the ids are not a list of integers"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the id does not exist"
     *     )
     * )
     */
    public function getFilmAction(
        string $idFilmList,
        FilmIndexRepositoryInterface $filmIndexRepository
    ): JsonResponse {
        try {
            $film = $filmIndexRepository->getFilm($idFilmList);
        } catch (\Throwable $e) {
            return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
        }

        $response = !$film->getItems() ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_OK;

        return new JsonResponse($film->toArray(), $response);
    }

    /**
     * @Operation(
     *     tags={"Films"},
     *     summary="Get popular films",
     *
     *     @SWG\Parameter(
     *         name="numResults",
     *         in="query",
     *         description="Maximum amount of results to be returned (10 by default)",
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
     *
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
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
     * @Operation(
     *     tags={"Films"},
     *     summary="Get current films in theatres",
     *
     *     @SWG\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort films by [releaseDate, rating, numRatings]",
     *         required=false,
     *         type="string"
     *     ),
     *
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
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
     * @Operation(
     *     tags={"Films"},
     *     summary="Get new films by platform",
     *
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
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
