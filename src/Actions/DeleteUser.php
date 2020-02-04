<?php

namespace App\Actions;

use App\Domain\User\ResolverUser;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct(UserRepository $userRepo, ResolverUser $resolverUser)
    {
        $this->userRepo = $userRepo;
        $this->resolverUser = $resolverUser;
    }

    public function __invoke(JsonResponder $responder, int $idCustomer, int $idUser)
    {
        $user = $this->userRepo->findOneBy(
            [
                'id' => $idUser,
                'customer' => $idCustomer
            ]
        );

        if (!is_null($user)) {
            $this->resolverUser->delete($user);
        }

        return $responder(null, Response::HTTP_NO_CONTENT);
    }
}
