<?php

namespace App\Actions;

use App\Domain\Services\SerializerService;
use App\Repository\SmartphoneRepository;
use App\Responder\JsonResponder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowProductDetails
 * @package App\Actions
 *
 * @Route("api/products/{id}", name="api_product_details", methods={"GET"})
 */
final class ShowProductDetails
{

    /** @var SmartphoneRepository */
    protected $smartphoneRepo;

    /** @var SerializerService */
    protected $serializer;

    /**
     * ShowProductDetails constructor.
     * @param SerializerService $serializer
     * @param SmartphoneRepository $smartphoneRepo
     */
    public function __construct(SerializerService $serializer, SmartphoneRepository $smartphoneRepo)
    {
        $this->serializer = $serializer;
        $this->smartphoneRepo = $smartphoneRepo;
    }

    /**
     * Show smartphone details
     *
     * @param JsonResponder $responder
     * @param int $id
     * @return Response
     */
    public function __invoke(JsonResponder $responder, int $id): Response
    {
        $smartphone = $this->smartphoneRepo->findOneBy(['id' => $id]);
        $data = $this->serializer->serializer($smartphone, ['groups' => ['showProduct', 'productDetails']]);

        if (is_null($smartphone)) {
            return $responder(
                [
                    "status" => "404 Ressource introuvable",
                    "message" => "Smartphone introuvable !"
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $responder($data, Response::HTTP_OK);
    }
}
