<?php

namespace App\Domain\User\Normalizer;

use App\Domain\Services\SerializerService;
use App\Entity\User;
use App\Entity\UserAddress;
use App\Repository\UserAddressRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class UserDetailsNormalizer implements ContextAwareNormalizerInterface
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
        return $data instanceof User && in_array('userDetails', $context['groups']);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        
        $data['_link']['self']['href'] = $this->urlGenerator->generate(
            'api_show_users',
            ['id' => $object->getCustomer()->getId()]
        );

        $data['_link']['delete']['href'] = $this->urlGenerator->generate(
            'api_delete_user',
            [
                'idCustomer' => $object->getCustomer()->getId(),
                'idUser' => $object->getId()
            ]
        );

        for ($i = 0; $i < count($object->getAddress()); $i++) {
            $data['_embedded']['address' . '_' . $i] = $this->normalizer->normalize(
                $object->getAddress()[$i],
                $format,
                ['groups' => ['showUserAddress']]
            );
        }

        $data['_embedded']['customer'] = $this->normalizer->normalize(
            $object->getCustomer(),
            $format,
            ['groups' => ['showCustomer']]
        );

        return $data;
    }
}
