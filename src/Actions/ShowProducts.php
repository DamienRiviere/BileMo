<?php

namespace App\Actions;

use App\Domain\Services\SerializerService;
use App\Repository\SmartphoneRepository;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowProducts
 * @package App\Actions
 *
 * @Route("/api/products", name="api_show_products", methods={"GET"})
 */
final class ShowProducts
{

    /** @var SmartphoneRepository */
    protected $smartRepo;

    /** @var SerializerService */
    protected $serializer;

    /**
     * ShowProducts constructor.
     * @param SmartphoneRepository $smartRepo
     * @param SerializerService $serializer
     */
    public function __construct(SmartphoneRepository $smartRepo, SerializerService $serializer)
    {
        $this->smartRepo = $smartRepo;
        $this->serializer = $serializer;
    }

    /**
     * @param JsonResponder $responder
     * @return Response
     */
    public function __invoke(JsonResponder $responder): Response
    {
        $products =  $this->smartRepo->findAll();
        $data = $this->serializer->serializer($products, ['groups' => ['showProduct', 'listProduct']]);

        if (is_null($products)) {
            return $responder(
                [
                    "status" => "404 Ressource introuvable",
                    "message" => "Liste des produits introuvable !"
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $responder($data, Response::HTTP_OK);
    }
}
