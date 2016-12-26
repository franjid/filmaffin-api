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

    public function indexAction(Request $request)
    {
        $title = $request->get('title');

        /** @var FilmRepositoryInterface $filmIndexRepository */
        $filmIndexRepository = $this->get(FilmRepositoryInterface::DIC_NAME);

        return new JsonResponse($filmIndexRepository->searchFilms($title), JsonResponse::HTTP_OK);
    }
}
