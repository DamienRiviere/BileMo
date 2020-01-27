<?php

namespace App\Actions;

use App\Domain\Services\SerializerService;
use App\Repository\SmartphoneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class ShowProducts
 * @package App\Actions
 *
 * @Route("/api/products", name="api_products", methods={"GET"})
 */
final class ShowProducts
{

    /** @var SmartphoneRepository */
    protected $smartRepo;

    /** @var SerializerService */
    protected $serializer;

    public function __construct(SmartphoneRepository $smartRepo, SerializerService $serializer)
    {
        $this->smartRepo = $smartRepo;
        $this->serializer = $serializer;
    }

    public function __invoke()
    {
        $products = $this->smartRepo->findAll();

        if (!$products) {
            throw new ResourceNotFoundException("Aucun produit n'a été trouver", 404);
        }

        $data = $this->serializer->serializerHandlingReferences($products);
        $response = new Response($data, 200);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Location', '/api/products');

        return $response;
    }
}
