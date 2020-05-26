<?php

namespace App\Controller;

use App\Component\Util\StringUtil;
use App\Repository\Index\Film\FilmRepositoryInterface;
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
     *     summary="Search films",
     *     @SWG\Parameter(
     *         name="title",
     *         in="query",
     *         description="Films with that title",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when there are no films matching the given title"
     *     )
     * )
     *
     *
     * @param Request                 $request
     * @param FilmRepositoryInterface $filmIndexRepository
     *
     * @return JsonResponse
     */
    public function searchAction(
        Request $request,
        FilmRepositoryInterface $filmIndexRepository
    ): JsonResponse
    {
        $title = StringUtil::removeDiacritics($request->query->get('title'));

        $films = $filmIndexRepository->searchFilms($title);
        $response = empty($films) ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_OK;

        return new JsonResponse($films, $response);
    }

    /**
     * @Operation(
     *     tags={"Films"},
     *     summary="Get film",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the id does not exist"
     *     )
     * )
     *
     * @param int                     $idFilm
     * @param FilmRepositoryInterface $filmIndexRepository
     *
     * @return JsonResponse
     */
    public function getFilmAction(
        int $idFilm,
        FilmRepositoryInterface $filmIndexRepository
    ): JsonResponse
    {
        $film = $filmIndexRepository->getFilm($idFilm);
        $response = empty($film) ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_OK;

        return new JsonResponse($film, $response);
    }

    /**
     * @Operation(
     *     tags={"Films"},
     *     summary="Get popular films",
     *     @SWG\Parameter(
     *         name="numResults",
     *         in="query",
     *         description="Maximum amount of results to be returned",
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
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the id does not exist"
     *     )
     * )
     *
     * @param Request                 $request
     * @param FilmRepositoryInterface $filmIndexRepository
     *
     * @return JsonResponse
     */
    public function getPopularFilmsAction(
        Request $request,
        FilmRepositoryInterface $filmIndexRepository
    ): JsonResponse
    {
        $numResults = $request->query->get('numResults');
        $numResults = $numResults !== null ? (int) $numResults : 10;
        $offset = $request->query->get('offset');
        $offset = $offset !== null ? (int) $offset : 0;

        $film = $filmIndexRepository->getPopularFilms($numResults, $offset);
        $response = empty($film) ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;

        return new JsonResponse($film, $response);
    }

    /**
     * @Operation(
     *     tags={"Films"},
     *     summary="Get current films in theatres",
     *     @SWG\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort films by [releaseDate, rating, numRatings]",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the id does not exist"
     *     )
     * )
     *
     * @param Request                 $request
     * @param FilmRepositoryInterface $filmIndexRepository
     *
     * @return JsonResponse
     */
    public function getFilmsInTheatresAction(
        Request $request,
        FilmRepositoryInterface $filmIndexRepository
    ): JsonResponse
    {
        $availableSort = ['releaseDate', 'rating', 'numRatings'];

        $sortBy = $request->query->get('sort');

        if ($sortBy !== null && !in_array($sortBy, $availableSort, true)) {
            return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($sortBy === null) {
            $sortBy = 'releaseDate'; // Default sort option
        }

        $film = $filmIndexRepository->getFilmsInTheatres($sortBy);
        $response = empty($film) ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;

        return new JsonResponse($film, $response);
    }
}
