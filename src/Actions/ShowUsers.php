<?php

namespace App\Actions;

use App\Domain\Services\SerializerService;
use App\Entity\Customer;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * ShowUsers constructor.
     * @param UserRepository $userRepo
     * @param SerializerService $serializer
     */
    public function __construct(UserRepository $userRepo, SerializerService $serializer)
    {
        $this->userRepo = $userRepo;
        $this->serializer = $serializer;
    }

    /**
     * @param JsonResponder $responder
     * @param Customer $customer
     * @return Response
     */
    public function __invoke(JsonResponder $responder, Customer $customer): Response
    {
        $users = $this->userRepo->findBy(['customer' => $customer]);
        $data = $this->serializer->serializer($users, ['groups' => ['showUser', 'listUser']]);

        if (is_null($users)) {
            return $responder(
                [
                    "status" => "404 Ressource introuvable",
                    "message" => "Liste des utilisateurs introuvable !"
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $responder($data, Response::HTTP_OK);
    }
}
