<?php

namespace App\Actions;

use App\Domain\Services\GenerateUrl;
use App\Domain\Services\Validator;
use App\Domain\User\ResolverUser;
use App\Entity\Customer;
use App\Responder\JsonResponder;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * NewUser constructor.
     * @param ResolverUser $resolverUser
     * @param Validator $validator
     * @param GenerateUrl $url
     */
    public function __construct(ResolverUser $resolverUser, Validator $validator, GenerateUrl $url)
    {
        $this->resolverUser = $resolverUser;
        $this->validator = $validator;
        $this->url = $url;
    }

    /**
     * Create a new user
     *
     * @SWG\Response(
     *     response="201",
     *     description="Create a new user for a customer."
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Show errors validation."
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Unique identifier of the customer",
     *     required=true
     * )
     * @SWG\Tag(name="user")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param Customer $customer
     * @param JsonResponder $responder
     * @return Response
     */
    public function __invoke(Request $request, Customer $customer, JsonResponder $responder): Response
    {
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
