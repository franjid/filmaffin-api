<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return new JsonResponse(['test' => 1], JsonResponse::HTTP_OK);
    }
}
