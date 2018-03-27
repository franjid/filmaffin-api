<?php

namespace AppBundle\Controller;

use Component\Util\StringUtil;
use Repository\Index\Film\FilmRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FilmsController extends BaseController
{
    /**
     * @Nelmio\ApiDocBundle\Annotation\ApiDoc(
     *  section="Films",
     *  resource=true,
     *  description="Get films",
     *  statusCodes={
     *     200 = "Returned when successful",
     *     404 = "Returned when there are no films matching the given title"
     *  },
     *  requirements={
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "requirement"="\w+",
     *          "description"="films with that title"
     *      }
     *  }
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchAction(Request $request)
    {
        $title = StringUtil::removeDiacritics($request->query->get('title'));

        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::DIC_NAME);

        $films = $filmIndexRepository->searchFilms($title);
        $response = empty($films) ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_OK;

        return new JsonResponse($films, $response);
    }

    /**
     * @Nelmio\ApiDocBundle\Annotation\ApiDoc(
     *  section="Films",
     *  resource=true,
     *  description="Get film",
     *  statusCodes={
     *     200 = "Returned when successful",
     *     404 = "Returned when the id does not exist"
     *  },
     *  requirements={
     *      {
     *          "name"="idFilm",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="films id"
     *      }
     *  }
     * )
     *
     * @param int $idFilm
     *
     * @return JsonResponse
     */
    public function getFilmAction($idFilm)
    {
        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::DIC_NAME);

        $film = $filmIndexRepository->getFilm($idFilm);
        $response = empty($film) ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_OK;

        return new JsonResponse($film, $response);
    }

    /**
     * @Nelmio\ApiDocBundle\Annotation\ApiDoc(
     *  section="Films",
     *  resource=true,
     *  description="Get popular films",
     *  statusCodes={
     *     200 = "Returned when successful",
     *     404 = "Returned when the id does not exist"
     *  },
     *  filters={
     *      {
     *          "name"="numResults",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Maximum amount of results to be returned"
     *      },
     *      {
     *          "name"="offset",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Offset from the first result you want to fetch"
     *      }
     *  }
     * )
     *
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getPopularFilmsAction(Request $request)
    {
        $numResults = $request->query->get('numResults');
        $numResults = !is_null($numResults) ? intval($numResults) : 10;
        $offset = $request->query->get('offset');
        $offset = !is_null($offset) ? intval($offset) : 0;

        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::DIC_NAME);

        $film = $filmIndexRepository->getPopularFilms($numResults, $offset);
        $response = empty($film) ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;

        return new JsonResponse($film, $response);
    }

    /**
     * @Nelmio\ApiDocBundle\Annotation\ApiDoc(
     *  section="Films",
     *  resource=true,
     *  description="Get current films in theatres",
     *  statusCodes={
     *     200 = "Returned when successful",
     *     404 = "Returned when the id does not exist"
     *  },
     *  filters={
     *      {
     *          "name"="sort",
     *          "dataType"="string",
     *          "requirement"="\w+",
     *          "description"="Sort films by [releaseDate, rating, numRatings]"
     *      }
     *  }
     * )
     *
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getFilmsInTheatresAction(Request $request)
    {
        $availableSort = ['releaseDate', 'rating', 'numRatings'];

        $sortBy = $request->query->get('sort');

        if (!is_null($sortBy) && !in_array($sortBy, $availableSort)) {
            return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
        } else if (is_null($sortBy)) {
            $sortBy = 'releaseDate'; // Default sort option
        }

        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::DIC_NAME);

        $film = $filmIndexRepository->getFilmsInTheatres($sortBy);
        $response = empty($film) ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_OK;

        return new JsonResponse($film, $response);
    }
}
