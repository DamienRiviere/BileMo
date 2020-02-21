<?php

namespace App\Actions;

use App\Domain\Common\Exception\AuthorizationException;
use App\Domain\Common\Exception\ValidationException;
use App\Domain\Services\CheckAuthorization;
use App\Domain\Services\GenerateUrl;
use App\Domain\Services\Validator;
use App\Domain\User\ResolverUser;
use App\Repository\CustomerRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
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

    /** @var CheckAuthorization */
    protected $checkAuthorization;

    /** @var CustomerRepository */
    protected $customerRepo;

    /**
     * NewUser constructor.
     * @param ResolverUser $resolverUser
     * @param Validator $validator
     * @param GenerateUrl $url
     * @param AuthorizationCheckerInterface $authorization
     * @param CheckAuthorization $checkAuthorization
     * @param CustomerRepository $customerRepo
     */
    public function __construct(
        ResolverUser $resolverUser,
        Validator $validator,
        GenerateUrl $url,
        AuthorizationCheckerInterface $authorization,
        CheckAuthorization $checkAuthorization,
        CustomerRepository $customerRepo
    ) {
        $this->resolverUser = $resolverUser;
        $this->validator = $validator;
        $this->url = $url;
        $this->authorization = $authorization;
        $this->checkAuthorization = $checkAuthorization;
        $this->customerRepo = $customerRepo;
    }

    /**
     * Create a new user
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws ValidationException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $customer = $this->customerRepo->findById($id);
//        $authorization = $this->authorization->isGranted('createUser', $customer);
//        $this->checkAuthorization->checkCreate($authorization);

        $dto = $this->resolverUser->createUserDTO($request->getContent());
        $this->validator->validate($dto);

        $user = $this->resolverUser->save($dto, $customer);

        return JsonResponder::response(
            null,
            Response::HTTP_CREATED,
            $this->url->generateHeader(
                "api_show_user_details",
                [
                    "customerId" => $customer->getId(),
                    "userId" => $user->getId()
                ]
            )
        );
    }
}
