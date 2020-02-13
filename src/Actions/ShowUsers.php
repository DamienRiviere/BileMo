<?php

namespace App\Actions;

use App\Domain\Helpers\AuthorizationHelper;
use App\Domain\Helpers\PaginationHelper;
use App\Domain\Services\SerializerService;
use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class ShowUsers
 * @package App\Actions
 *
 * @Route("api/customers/{id}/users", name="api_show_users", methods={"GET"})
 */
final class ShowUsers
{

    /** @var UserRepository */
    protected $userRepo;

    /** @var SerializerService */
    protected $serializer;

    /** @var PaginationHelper */
    protected $paginationHelper;

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var AuthorizationHelper */
    protected $authorizationHelper;

    /**
     * ShowUsers constructor.
     * @param UserRepository $userRepo
     * @param SerializerService $serializer
     * @param PaginationHelper $paginationHelper
     * @param AuthorizationCheckerInterface $authorization
     * @param AuthorizationHelper $authorizationHelper
     */
    public function __construct(
        UserRepository $userRepo,
        SerializerService $serializer,
        PaginationHelper $paginationHelper,
        AuthorizationCheckerInterface $authorization,
        AuthorizationHelper $authorizationHelper
    ) {
        $this->userRepo = $userRepo;
        $this->serializer = $serializer;
        $this->paginationHelper = $paginationHelper;
        $this->authorization = $authorization;
        $this->authorizationHelper = $authorizationHelper;
    }

    /**
     * @param Request $request
     * @param JsonResponder $responder
     * @param Customer $customer
     * @return Response
     */
    public function __invoke(Request $request, JsonResponder $responder, Customer $customer): Response
    {
        $users = $this->userRepo->findByCustomer($customer);
        $authorization = $this->authorization->isGranted('view', $users);

        if (!$authorization) {
            return $responder($this->authorizationHelper->checkAccess($authorization), Response::HTTP_FORBIDDEN);
        }

        $page = $this->paginationHelper->checkPage($request, $users, User::LIMIT_PER_PAGE);

        if (is_array($page)) {
            return $responder($page, Response::HTTP_NOT_FOUND);
        }

        $users = $this->userRepo->findAllUser($page, $customer, $request->query->get('filter'));
        $data = $this->serializer->serializer(
            $users,
            [
                'groups' => ['showUser', 'listUser'],
                'page' => $page,
                'customer' => $customer
            ]
        );

        return $responder($data, Response::HTTP_OK);
    }
}
