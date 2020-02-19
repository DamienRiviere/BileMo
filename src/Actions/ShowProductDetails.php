<?php

namespace App\Actions;

use App\Domain\Services\HttpCache;
use App\Domain\Services\SerializerService;
use App\Repository\SmartphoneRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
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

    /** @var HttpCache */
    protected $cache;

    /**
     * ShowProductDetails constructor.
     * @param SerializerService $serializer
     * @param SmartphoneRepository $smartphoneRepo
     * @param HttpCache $cache
     */
    public function __construct(
        SerializerService $serializer,
        SmartphoneRepository $smartphoneRepo,
        HttpCache $cache
    ) {
        $this->serializer = $serializer;
        $this->smartphoneRepo = $smartphoneRepo;
        $this->cache = $cache;
    }

    /**
     * Show smartphone details
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws EntityNotFoundException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $smartphone = $this->smartphoneRepo->findOneById($id);
        $data = $this->serializer->serializer($smartphone, ['groups' => ['showProduct', 'productDetails']]);

        $response = JsonResponder::response($data, Response::HTTP_OK);
        $response = $this->cache->setHttpCache($response, $request, 3600);

        return $response;
    }
}
