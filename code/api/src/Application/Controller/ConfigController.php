<?php

namespace App\Application\Controller;

use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
class ConfigController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/config",
     *     tags={"Config"},
     *     summary="Get config",
     *     description="Retrieves the configuration.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function indexAction(): JsonResponse
    {
        return new JsonResponse([
            'ads' => [
                'enabled' => true,
                'num_views_before_show_ad' => 10,
            ],
        ],
            JsonResponse::HTTP_OK
        );
    }
}
