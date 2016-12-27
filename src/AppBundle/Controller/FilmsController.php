<?php

namespace AppBundle\Controller;

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
        $title = $request->get('title');

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
}
