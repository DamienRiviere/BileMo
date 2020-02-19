<?php

namespace App\Domain\User\Normalizer;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class UserDetailsNormalizer implements ContextAwareNormalizerInterface
{

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    public function __construct(
        ObjectNormalizer $normalizer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User && in_array('userDetails', $context['groups']);
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // _link
        $data = $this->getSelfLink($data, $object);
        $data = $this->getDeleteLink($data, $object);

        // _embedded
        $data = $this->getEmbeddedAddress($data, $object);
        $data = $this->getEmbeddedCustomer($data, $object);

        return $data;
    }

    public function getSelfLink(array $data, User $object): array
    {
        $data['_link']['list']['href'] = $this->urlGenerator->generate(
            User::SHOW_USERS,
            [
                'id' => $object->getCustomer()->getId()
            ]
        );

        return $data;
    }

    public function getDeleteLink(array $data, User $object): array
    {
        $data['_link']['delete']['href'] = $this->urlGenerator->generate(
            User::DELETE_USER,
            [
                'customerId' => $object->getCustomer()->getId(),
                'userId' => $object->getId()
            ]
        );

        return $data;
    }

    public function getEmbeddedAddress(array $data, User $object): array
    {
        for ($i = 0; $i < count($object->getAddress()); $i++) {
            $data['_embedded']['address' . '_' . $i] = $this->normalizer->normalize(
                $object->getAddress()[$i],
                'json',
                ['groups' => 'showUserAddress']
            );
        }

        return $data;
    }

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
