<?php

namespace App\Domain\User\Normalizer;

use App\Domain\Services\Pagination;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class UsersNormalizer
 * @package App\Domain\User\Normalizer
 */
final class UsersNormalizer implements ContextAwareNormalizerInterface
{

    /** @var UserRepository */
    protected $userRepo;

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /**
     * UsersNormalizer constructor.
     * @param ObjectNormalizer $normalizer
     * @param UserRepository $userRepo
     * @param UrlGeneratorInterface $urlGenerator.
     */
    public function __construct(
        ObjectNormalizer $normalizer,
        UserRepository $userRepo,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->normalizer = $normalizer;
        $this->userRepo = $userRepo;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User && in_array('listUser', $context['groups']);
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     * @return array
     * @throws ExceptionInterface
     */
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
        $data = $this->getFirstPageLink($data, $context);
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

    /**
     * @param array $data
     * @param User $object
     * @return array
     */
    public function getSelfLink(array $data, User $object): array
    {
        $data['_link']['self']['href'] = $this->urlGenerator->generate(
            User::SHOW_USER_DETAILS,
            [
                'userId' => $object->getId(),
                'customerId' => $object->getCustomer()->getId()
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param array $context
     * @return array
     */
    public function getFirstPageLink(array $data, array $context): array
    {
        $data['_link']['first']['href'] = $this->urlGenerator->generate(
            User::SHOW_USERS,
            [
                'id' => $context['customer']->getId(),
                'page' => 1
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param array $context
     * @param Pagination $pagination
     * @return array
     */
    public function getLastPageLink(array $data, array $context, Pagination $pagination): array
    {
        $data['_link']['last']['href'] = $this->urlGenerator->generate(
            User::SHOW_USERS,
            [
                'id' => $context['customer']->getId(),
                'page' => $pagination->getLastPage()
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param array $context
     * @param Pagination $pagination
     * @return array
     */
    public function getNextPageLink(array $data, array $context, Pagination $pagination): array
    {
        $data['_link']['next']['href'] = $this->urlGenerator->generate(
            User::SHOW_USERS,
            [
                'id' => $context['customer']->getId(),
                'page' => $pagination->getNextPage()
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param array $context
     * @param Pagination $pagination
     * @return array
     */
    public function getPreviousPageLink(array $data, array $context, Pagination $pagination): array
    {
        $data['_link']['prev']['href'] = $this->urlGenerator->generate(
            User::SHOW_USERS,
            [
                'id' => $context['customer']->getId(),
                'page' => $pagination->getPreviousPage()
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param User $object
     * @return array
     * @throws ExceptionInterface
     */
    public function getEmbeddedCustomer(array $data, User $object): array
    {
        $data['_embedded']['customer'] = $this->normalizer->normalize(
            $object->getCustomer(),
            'json',
            ['groups' => 'showCustomer']
        );

        return $data;
    }
}
