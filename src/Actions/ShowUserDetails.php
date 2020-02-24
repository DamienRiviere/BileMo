<?php

namespace App\Actions;

use App\Domain\Common\Exception\AuthorizationException;
use App\Domain\Services\CheckAuthorization;
use App\Domain\Services\HttpCache;
use App\Domain\Services\SerializerService;
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
 * Class ShowUserDetails
 * @package App\Actions
 *
 * @Route("api/customers/{customerId}/users/{userId}", name="api_show_user_details", methods={"GET"})
 */
final class ShowUserDetails
{

    /** @var UserRepository */
    protected $userRepo;

    /** @var SerializerService */
    protected $serializer;

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var CheckAuthorization */
    protected $checkAuthorization;

    /** @var HttpCache */
    protected $cache;

    /** @var CustomerRepository */
    protected $customerRepo;

    /**
     * ShowUserDetails constructor.
     * @param UserRepository $userRepo
     * @param SerializerService $serializer
     * @param AuthorizationCheckerInterface $authorization
     * @param HttpCache $cache
     * @param CheckAuthorization $checkAuthorization
     * @param CustomerRepository $customerRepo
     */
    public function __construct(
        UserRepository $userRepo,
        SerializerService $serializer,
        AuthorizationCheckerInterface $authorization,
        HttpCache $cache,
        CheckAuthorization $checkAuthorization,
        CustomerRepository $customerRepo
    ) {
        $this->userRepo = $userRepo;
        $this->serializer = $serializer;
        $this->authorization = $authorization;
        $this->cache = $cache;
        $this->checkAuthorization = $checkAuthorization;
        $this->customerRepo = $customerRepo;
    }

    /**
     * Show user details of a customer
     *
     * @param Request $request
     * @param int $customerId
     * @param int $userId
     * @return Response
     * @throws AuthorizationException
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(Request $request, int $customerId, int $userId)
    {
        $user = $this->userRepo->findOneById($userId);
        $customer = $this->customerRepo->findById($customerId);
        $authorization = $this->authorization->isGranted('userDetails', ['user' => $user, 'customer' => $customer]);
        $this->checkAuthorization->checkAccess($authorization);

        $data = $this->serializer->serializer($user, ['groups' => ['showUser', 'userDetails']]);

        $response = JsonResponder::response($data, Response::HTTP_OK);
        $response = $this->cache->setHttpCache($response, $request, 180);

        return $response;
    }
}
