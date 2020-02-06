<?php

namespace App\Domain\User\Normalizer;

use App\Entity\User;
use App\Repository\CustomerRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class UsersNormalizer implements ContextAwareNormalizerInterface
{

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var ObjectNormalizer */
    protected $normalizer;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ObjectNormalizer $normalizer
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->normalizer = $normalizer;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User && in_array('listUser', $context['groups']);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data['_link']['self']['href'] = $this->urlGenerator->generate(
            'api_show_users_details',
            [
                'idUser' => $object->getId(),
                'idCustomer' => $object->getCustomer()->getId()
            ]
        );

        $data['_embedded']['customer'] = $this->normalizer->normalize(
            $object->getCustomer(),
            $format,
            ['groups' => ['showCustomer']]
        );

        return $data;
    }
}
