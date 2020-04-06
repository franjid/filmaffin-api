<?php

namespace AppBundle\Controller;

use Component\Util\StringUtil;
use Repository\Index\Film\FilmRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class FilmsController extends BaseController
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchAction(Request $request): JsonResponse
    {
        $title = StringUtil::removeDiacritics($request->query->get('title'));

        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::class);

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
     *
     * @param int $idFilm
     *
     * @return JsonResponse
     */
    public function getFilmAction($idFilm): JsonResponse
    {
        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::class);

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
     *
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getPopularFilmsAction(Request $request): JsonResponse
    {
        $numResults = $request->query->get('numResults');
        $numResults = $numResults !== null ? (int) $numResults : 10;
        $offset = $request->query->get('offset');
        $offset = $offset !== null ? (int) $offset : 0;

        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::class);

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
     *
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getFilmsInTheatresAction(Request $request): JsonResponse
    {
        $availableSort = ['releaseDate', 'rating', 'numRatings'];

        $sortBy = $request->query->get('sort');

        if ($sortBy !== null && !in_array($sortBy, $availableSort)) {
            return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($sortBy === null) {
            $sortBy = 'releaseDate'; // Default sort option
        }

        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::class);

        $film = $filmIndexRepository->getFilmsInTheatres($sortBy);
        $response = empty($film) ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;

        return new JsonResponse($film, $response);
    }
}
