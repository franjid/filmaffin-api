<?php

namespace App\Application\Controller;

use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConfigController extends AbstractController
{
    /**
     * @Operation(
     *     tags={"Config"},
     *     summary="Get config",
     *
     *     @SWG\Response(
     *         response="200",
     *         description=""
     *     ),
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
