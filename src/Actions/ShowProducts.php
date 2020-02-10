<?php

namespace App\Actions;

use App\Domain\Helpers\PaginationHelper;
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
    protected $smartRepo;

    /** @var SerializerService */
    protected $serializer;

    /** @var PaginationHelper */
    protected $paginationHelper;

    /**
     * ShowProducts constructor.
     * @param SmartphoneRepository $smartRepo
     * @param SerializerService $serializer
     * @param PaginationHelper $paginationHelper
     */
    public function __construct(
        SmartphoneRepository $smartRepo,
        SerializerService $serializer,
        PaginationHelper $paginationHelper
    ) {
        $this->smartRepo = $smartRepo;
        $this->serializer = $serializer;
        $this->paginationHelper = $paginationHelper;
    }

    /**
     * @param Request $request
     * @param JsonResponder $responder
     * @return Response
     */
    public function __invoke(Request $request, JsonResponder $responder): Response
    {
        $page = $this->paginationHelper->checkPage($request, $this->smartRepo->findAll(), Smartphone::LIMIT_PER_PAGE);

        if (is_array($page)) {
            return $responder($page, Response::HTTP_NOT_FOUND);
        }

        $products =  $this->smartRepo->findAllSmartphone($page, $request->query->get('filter'));
        $data = $this->serializer->serializer(
            $products,
            [
                'groups' => ['showProduct', 'listProduct',],
                'page' => $page
            ]
        );

        return $responder($data, Response::HTTP_OK);
    }
}
