<?php

namespace App\Actions;

use App\Domain\Common\Exception\AuthorizationException;
use App\Domain\Services\CheckAuthorization;
use App\Domain\User\ResolverUser;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class DeleteUser
 * @package App\Actions
 *
 * @Route("/api/customers/{customerId}/users/{userId}", name="api_delete_user", methods={"DELETE"})
 */
final class DeleteUser
{

    /** @var UserRepository */
    protected $userRepo;

    /** @var ResolverUser */
    protected $resolverUser;

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var CheckAuthorization */
    protected $checkAuthorization;

    /** @var CustomerRepository */
    protected $customerRepo;

    /**
     * DeleteUser constructor.
     * @param UserRepository $userRepo
     * @param ResolverUser $resolverUser
     * @param AuthorizationCheckerInterface $authorization
     * @param CheckAuthorization $checkAuthorization
     * @param CustomerRepository $customerRepo
     */
    public function __construct(
        UserRepository $userRepo,
        ResolverUser $resolverUser,
        AuthorizationCheckerInterface $authorization,
        CheckAuthorization $checkAuthorization,
        CustomerRepository $customerRepo
    ) {
        $this->userRepo = $userRepo;
        $this->resolverUser = $resolverUser;
        $this->authorization = $authorization;
        $this->checkAuthorization = $checkAuthorization;
        $this->customerRepo = $customerRepo;
    }

    /**
     * Delete user
     *
     * @param int $customerId
     * @param int $userId
     * @return Response
     * @throws AuthorizationException
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(int $customerId, int $userId)
    {
        $user = $this->userRepo->findOneById($userId);
        $customer = $this->customerRepo->findById($customerId);
//        $authorization = $this->authorization->isGranted('userDelete', ['user' => $user, 'customer' => $customer]);
//        $this->checkAuthorization->checkDelete($authorization);

        $this->resolverUser->delete($user);

        return JsonResponder::response(null, Response::HTTP_NO_CONTENT);
    }
}
