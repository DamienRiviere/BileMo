<?php

namespace App\Actions;

use App\Domain\Helpers\AuthorizationHelper;
use App\Domain\Services\GenerateUrl;
use App\Domain\Services\Validator;
use App\Domain\User\ResolverUser;
use App\Entity\Customer;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class NewUser
 * @package App\Actions
 *
 * @Route("/api/customers/{id}/users", name="api_new_user", methods={"POST"})
 */
final class NewUser
{

    /** @var ResolverUser */
    protected $resolverUser;

    /** @var Validator */
    protected $validator;

    /** @var GenerateUrl */
    protected $url;

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var AuthorizationHelper */
    protected $authorizationHelper;

    /**
     * NewUser constructor.
     * @param ResolverUser $resolverUser
     * @param Validator $validator
     * @param GenerateUrl $url
     * @param AuthorizationCheckerInterface $authorization
     * @param AuthorizationHelper $authorizationHelper
     */
    public function __construct(
        ResolverUser $resolverUser,
        Validator $validator,
        GenerateUrl $url,
        AuthorizationCheckerInterface $authorization,
        AuthorizationHelper $authorizationHelper
    ) {
        $this->resolverUser = $resolverUser;
        $this->validator = $validator;
        $this->url = $url;
        $this->authorization = $authorization;
        $this->authorizationHelper = $authorizationHelper;
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @param JsonResponder $responder
     * @return Response
     */
    public function __invoke(Request $request, Customer $customer, JsonResponder $responder): Response
    {
        $authorization = $this->authorization->isGranted('create', $customer);

        if (!$authorization) {
            return $responder($this->authorizationHelper->checkCreate($authorization), Response::HTTP_FORBIDDEN);
        }

        $dto = $this->resolverUser->createUserDTO($request->getContent());
        $errors = $this->validator->validate($dto, '400 Requête non conforme !');

        if (!is_null($errors)) {
            return $responder($errors, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->resolverUser->save($dto, $customer);

        return $responder(
            null,
            Response::HTTP_CREATED,
            $this->url->generateHeader(
                "api_show_users_details",
                [
                    "idCustomer" => $customer->getId(),
                    "idUser" => $user->getId()
                ]
            )
        );
    }
}
