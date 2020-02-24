<?php

namespace App\Actions;

use App\Domain\Common\Exception\PageNotFoundException;
use App\Domain\Helpers\PaginationHelper;
use App\Domain\Services\HttpCache;
use App\Domain\Services\SerializerService;
use App\Entity\Smartphone;
use App\Repository\SmartphoneRepository;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Request;
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
    protected $smartphoneRepo;

    /** @var SerializerService */
    protected $serializer;

    /** @var PaginationHelper */
    protected $paginationHelper;

    /** @var HttpCache */
    protected $cache;

    /**
     * ShowProducts constructor.
     * @param SmartphoneRepository $smartphoneRepo
     * @param SerializerService $serializer
     * @param PaginationHelper $paginationHelper
     * @param HttpCache $cache
     */
    public function __construct(
        SmartphoneRepository $smartphoneRepo,
        SerializerService $serializer,
        PaginationHelper $paginationHelper,
        HttpCache $cache
    ) {
        $this->smartphoneRepo = $smartphoneRepo;
        $this->serializer = $serializer;
        $this->paginationHelper = $paginationHelper;
        $this->cache = $cache;
    }

    /**
     * Show all products
     *
     * @param Request $request
     * @return Response
     * @throws PageNotFoundException
     */
    public function __invoke(Request $request): Response
    {
        $page = $this->paginationHelper->checkPage(
            $request,
            $this->smartphoneRepo->findAll(),
            Smartphone::LIMIT_PER_PAGE
        );
        $products =  $this->smartphoneRepo->findAllSmartphone($page, $request->query->get('filter'));
        $data = $this->serializer->serializer(
            $products,
            [
                'groups' => ['showProduct', 'listProduct',],
                'page' => $page
            ]
        );

        $response = JsonResponder::response($data, Response::HTTP_OK);
        $response = $this->cache->setHttpCache($response, $request, 180);

        return $response;
    }
}
