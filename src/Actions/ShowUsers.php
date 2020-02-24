<?php

namespace App\Actions;

use App\Domain\Common\Exception\AuthorizationException;
use App\Domain\Common\Exception\PageNotFoundException;
use App\Domain\Helpers\PaginationHelper;
use App\Domain\Services\CheckAuthorization;
use App\Domain\Services\HttpCache;
use App\Domain\Services\SerializerService;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
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

    /** @var CustomerRepository */
    protected $customerRepo;

    /** @var UserRepository  */
    protected $userRepo;

    /** @var SerializerService */
    protected $serializer;

    /** @var PaginationHelper */
    protected $paginationHelper;

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var CheckAuthorization */
    protected $checkAuthorization;

    /** @var HttpCache */
    protected $cache;

    /**
     * ShowUsers constructor.
     * @param UserRepository $userRepo
     * @param CustomerRepository $customerRepo
     * @param SerializerService $serializer
     * @param PaginationHelper $paginationHelper
     * @param AuthorizationCheckerInterface $authorization
     * @param HttpCache $cache
     * @param CheckAuthorization $checkAuthorization
     */
    public function __construct(
        UserRepository $userRepo,
        CustomerRepository $customerRepo,
        SerializerService $serializer,
        PaginationHelper $paginationHelper,
        AuthorizationCheckerInterface $authorization,
        HttpCache $cache,
        CheckAuthorization $checkAuthorization
    ) {
        $this->userRepo = $userRepo;
        $this->customerRepo = $customerRepo;
        $this->serializer = $serializer;
        $this->paginationHelper = $paginationHelper;
        $this->authorization = $authorization;
        $this->cache = $cache;
        $this->checkAuthorization = $checkAuthorization;
    }

    /**
     * Show users of a customer
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws PageNotFoundException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $customer = $this->customerRepo->findById($id);
        $users = $this->userRepo->findByCustomer($customer);
        $authorization = $this->authorization->isGranted('usersList', ['users' => $users, 'customer' => $customer]);
        $this->checkAuthorization->checkAccess($authorization);

        $page = $this->paginationHelper->checkPage($request, $users, User::LIMIT_PER_PAGE);

        $paginated = $this->userRepo->findAllUser($page, $customer, $request->query->get('filter'));
        $data = $this->serializer->serializer(
            $paginated,
            [
                'groups' => ['showUser', 'listUser'],
                'page' => $page,
                'customer' => $customer
            ]
        );

        $response = JsonResponder::response($data, Response::HTTP_OK);
        $response = $this->cache->setHttpCache($response, $request, 180);

        return $response;
    }
}
