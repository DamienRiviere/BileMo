<?php

namespace App\Domain\User\Normalizer;

use App\Domain\Services\Hateoas;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class UserDetailsNormalizer implements ContextAwareNormalizerInterface
{

    /** @var Hateoas */
    protected $hateoas;

    /** @var ObjectNormalizer */
    protected $normalizer;

    public function __construct(
        ObjectNormalizer $normalizer,
        Hateoas $hateoas
    ) {
        $this->normalizer = $normalizer;
        $this->hateoas = $hateoas;
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
        $data = $this->hateoas->setLink(
            $data,
            User::SHOW_USERS,
            [
                'id' => $object->getCustomer()->getId()
            ],
            'self'
        );

        return $data;
    }

    public function getDeleteLink(array $data, User $object): array
    {
        $data = $this->hateoas->setLink(
            $data,
            User::DELETE_USER,
            [
                'idCustomer' => $object->getCustomer()->getId(),
                'idUser' => $object->getId()
            ],
            'delete'
        );

        return $data;
    }

    public function getEmbeddedAddress(array $data, User $object): array
    {
        for ($i = 0; $i < count($object->getAddress()); $i++) {
            $data = $this->hateoas->setEmbedded(
                $data,
                $object->getAddress()[$i],
                'address' . '_' . $i,
                'showUserAddress'
            );
        }

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
