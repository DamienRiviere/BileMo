<?php

namespace App\Actions;

use App\Domain\Helpers\AuthorizationHelper;
use App\Domain\Services\SerializerService;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var AuthorizationHelper */
    protected $authorizationHelper;

    /**
     * ShowUserDetails constructor.
     * @param UserRepository $userRepo
     * @param SerializerService $serializer
     * @param AuthorizationCheckerInterface $authorization
     * @param AuthorizationHelper $authorizationHelper
     */
    public function __construct(
        UserRepository $userRepo,
        SerializerService $serializer,
        AuthorizationCheckerInterface $authorization,
        AuthorizationHelper $authorizationHelper
    ) {
        $this->userRepo = $userRepo;
        $this->serializer = $serializer;
        $this->authorization = $authorization;
        $this->authorizationHelper = $authorizationHelper;
    }

    /**
     * Show user details of a customer
     *
     * @param JsonResponder $responder
     * @param int $idCustomer
     * @param int $idUser
     * @return Response
     */
    public function __invoke(JsonResponder $responder, int $idCustomer, int $idUser)
    {
        $user = $this->userRepo->findOneBy(['customer' => $idCustomer, 'id' => $idUser]);
        $authorization = $this->authorization->isGranted('view', $user);

        if (!$authorization) {
            return $responder($this->authorizationHelper->checkAccess($authorization), Response::HTTP_FORBIDDEN);
        }

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
