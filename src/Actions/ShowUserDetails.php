<?php

namespace App\Actions;

use App\Domain\Services\SerializerService;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowUserDetails
 * @package App\Actions
 *
 * @Route("api/customers/{idCustomer}/users/{idUser}", name="api_show_user_details", methods={"GET"})
 */
final class ShowUserDetails
{

    /** @var UserRepository */
    protected $userRepo;

    /** @var SerializerService */
    protected $serializer;

    /**
     * ShowUserDetails constructor.
     * @param UserRepository $userRepo
     * @param SerializerService $serializer
     */
    public function __construct(UserRepository $userRepo, SerializerService $serializer)
    {
        $this->userRepo = $userRepo;
        $this->serializer = $serializer;
    }

    /**
     * Show user details of a customer
     *
     * @SWG\Response(
     *     response="200",
     *     description="Return user details of a customer."
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Return a 404 not found if the user don't exist.",
     *     examples={"status": "404 Ressource introuvable", "message": "Utilisateur introuvable !"}
     * )
     * @SWG\Parameter(
     *     name="idCustomer",
     *     in="path",
     *     type="integer",
     *     description="Unique identifier of the customer.",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="idUser",
     *     in="path",
     *     type="integer",
     *     description="Unique identifier of the user.",
     *     required=true
     * )
     * @SWG\Tag(name="user")
     * @Security(name="Bearer")
     *
     * @param JsonResponder $responder
     * @param int $idCustomer
     * @param int $idUser
     * @return Response
     */
    public function __invoke(JsonResponder $responder, int $idCustomer, int $idUser)
    {
        $user = $this->userRepo->findOneBy(
            [
                'customer' => $idCustomer,
                'id' => $idUser
            ]
        );
        $data = $this->serializer->serializer($user, ['groups' => ['showUser', 'userDetails']]);

        if (is_null($user)) {
            return $responder(
                [
                    "status" => "404 Ressource introuvable",
                    "message" => "Utilisateur introuvable !"
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $responder($data, Response::HTTP_OK);
    }
}
