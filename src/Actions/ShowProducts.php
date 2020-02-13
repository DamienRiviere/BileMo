<?php

namespace App\Actions;

use App\Domain\Helpers\PaginationHelper;
use App\Domain\Services\SerializerService;
use App\Entity\Smartphone;
use App\Repository\SmartphoneRepository;
use App\Responder\JsonResponder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
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
     * Show all products
     *
     * @SWG\Response(
     *     response="200",
     *     description="Return all products of BileMo.",
     *     @Model(type=Smartphone::class, groups={"showProduct", "display", "storage", "camera", "battery"})
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Return a 404 not found if the page parameter don't exist.",
     *     examples={"status": "404 Ressource introuvable", "message": "Liste introuvable !"}
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="path",
     *     type="integer",
     *     description="Page of the list.",
     *     required=false
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="path",
     *     type="string",
     *     description="Filter by name or os of the product.",
     *     required=false
     * )
     * @SWG\Tag(name="product")
     * @Security(name="Bearer")
     *
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
