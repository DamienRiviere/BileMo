<?php

namespace App\Actions;

use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Test
 * @package App\Actions
 *
 * @Route("/test", name="test")
 */
class Test
{

    protected $customerRepo;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function __invoke()
    {
        $customer = json_encode($this->customerRepo->findOneBy(["id" => 1]));

        return new JsonResponse($customer);
    }
}
