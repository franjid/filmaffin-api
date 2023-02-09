<?php

namespace App\Application\Controller;

use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdsController extends AbstractController
{
    /**
     * @Operation(
     *     tags={"Ads"},
     *     summary="Get ads config",
     *     @SWG\Response(
     *         response="200",
     *         description=""
     *     ),
     * )
     *
     * @return JsonResponse
     */
    public function configAction(): JsonResponse
    {
        return new JsonResponse([
            "enabled" => true,
            "num_views_before_show_ad" => 10,
        ],
            JsonResponse::HTTP_OK
        );
    }
}
