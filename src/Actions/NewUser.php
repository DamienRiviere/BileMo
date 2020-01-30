<?php

namespace App\Actions;

use App\Domain\Services\Validator;
use App\Domain\User\ResolverUser;
use App\Entity\Customer;
use App\Responder\JsonResponder;
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

    /**
     * NewUser constructor.
     * @param ResolverUser $resolverUser
     * @param Validator $validator
     */
    public function __construct(ResolverUser $resolverUser, Validator $validator)
    {
        $this->resolverUser = $resolverUser;
        $this->validator = $validator;
    }

    /**
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

        $this->resolverUser->save($dto, $customer);

        return $responder(null, Response::HTTP_CREATED);
    }
}
