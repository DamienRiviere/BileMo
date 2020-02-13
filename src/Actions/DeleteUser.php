<?php

namespace App\Actions;

use App\Domain\Helpers\AuthorizationHelper;
use App\Domain\User\ResolverUser;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class DeleteUser
 * @package App\Actions
 *
 * @Route("/api/customers/{idCustomer}/users/{idUser}", name="api_delete_user", methods={"DELETE"})
 */
final class DeleteUser
{

    /** @var UserRepository */
    protected $userRepo;

    /** @var ResolverUser */
    protected $resolverUser;

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var AuthorizationHelper */
    protected $authorizationHelper;

    /**
     * DeleteUser constructor.
     * @param UserRepository $userRepo
     * @param ResolverUser $resolverUser
     * @param AuthorizationCheckerInterface $authorization
     * @param AuthorizationHelper $authorizationHelper
     */
    public function __construct(
        UserRepository $userRepo,
        ResolverUser $resolverUser,
        AuthorizationCheckerInterface $authorization,
        AuthorizationHelper $authorizationHelper
    ) {
        $this->userRepo = $userRepo;
        $this->resolverUser = $resolverUser;
        $this->authorization = $authorization;
        $this->authorizationHelper = $authorizationHelper;
    }

    /**
     * @param JsonResponder $responder
     * @param int $idCustomer
     * @param int $idUser
     * @return Response
     */
    public function __invoke(JsonResponder $responder, int $idCustomer, int $idUser)
    {
        $user = $this->userRepo->findOneBy(['id' => $idUser, 'customer' => $idCustomer]);
        $authorization = $this->authorization->isGranted('delete', $user);

        if (!$authorization) {
            return $responder($this->authorizationHelper->checkDelete($authorization), Response::HTTP_FORBIDDEN);
        }

        if (!is_null($user)) {
            $this->resolverUser->delete($user);
        }

        return $responder(null, Response::HTTP_NO_CONTENT);
    }
}
