<?php

namespace App\Domain\User\Normalizer;

use App\Domain\Services\Hateoas;
use App\Domain\Services\Pagination;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class UsersNormalizer implements ContextAwareNormalizerInterface
{

    /** @var UserRepository */
    protected $userRepo;

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var Hateoas */
    protected $hateoas;

    public function __construct(
        ObjectNormalizer $normalizer,
        Hateoas $hateoas,
        UserRepository $userRepo
    ) {
        $this->normalizer = $normalizer;
        $this->hateoas = $hateoas;
        $this->userRepo = $userRepo;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User && in_array('listUser', $context['groups']);
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $pagination = new Pagination(
            User::LIMIT_PER_PAGE,
            $this->userRepo->findBy(['customer' => $context['customer']]),
            $context['page']
        );

        // _link
        $data = $this->getSelfLink($data, $object);
        $data = $this->getFirstPageLink($data, $context, $pagination);
        $data = $this->getLastPageLink($data, $context, $pagination);

        if ($context['page'] < $pagination->getPages()) {
            $data = $this->getNextPageLink($data, $context, $pagination);
        }

        if ($context['page'] >= 2) {
            $data = $this->getPreviousPageLink($data, $context, $pagination);
        }

        // _embedded
        $data = $this->getEmbeddedCustomer($data, $object);

        return $data;
    }

    public function getSelfLink(array $data, User $object): array
    {
        $data = $this->hateoas->setLink(
            $data,
            User::SHOW_USER_DETAILS,
            [
                'idUser' => $object->getId(),
                'idCustomer' => $object->getCustomer()->getId()
            ],
            'self'
        );

        return $data;
    }

    public function getFirstPageLink(array $data, array $context, Pagination $pagination): array
    {
        $data = $this->hateoas->setLink(
            $data,
            User::SHOW_USERS,
            [
                'id' => $context['customer']->getId(),
                'page' => $pagination->getFirstPage()
            ],
            'first'
        );

        return $data;
    }

    public function getLastPageLink(array $data, array $context, Pagination $pagination): array
    {
        $data = $this->hateoas->setLink(
            $data,
            User::SHOW_USERS,
            [
                'id' => $context['customer']->getId(),
                'page' => $pagination->getLastPage()
            ],
            'last'
        );

        return $data;
    }

    public function getNextPageLink(array $data, array $context, Pagination $pagination): array
    {
        $data = $this->hateoas->setLink(
            $data,
            User::SHOW_USERS,
            [
                'id' => $context['customer']->getId(),
                'page' => $pagination->getNextPage()
            ],
            'next'
        );

        return $data;
    }

    public function getPreviousPageLink(array $data, array $context, Pagination $pagination): array
    {
        $data = $this->hateoas->setLink(
            $data,
            User::SHOW_USERS,
            [
                'id' => $context['customer']->getId(),
                'page' => $pagination->getPreviousPage()
            ],
            'prev'
        );

        return $data;
    }

    public function getEmbeddedCustomer(array $data, User $object): array
    {
        $data = $this->hateoas->setEmbedded(
            $data,
            $object->getCustomer(),
            'customer',
            'showCustomer'
        );

        return $data;
    }
}
