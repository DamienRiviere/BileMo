<?php

namespace App\Domain\Product\Normalizer;

use App\Domain\Services\Hateoas;
use App\Entity\Smartphone;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ProductDetailsNormalizer implements ContextAwareNormalizerInterface
{

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var Hateoas */
    protected $hateoas;

    public function __construct(ObjectNormalizer $normalizer, Hateoas $hateoas)
    {
        $this->normalizer = $normalizer;
        $this->hateoas = $hateoas;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Smartphone && in_array('productDetails', $context['groups']);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // _link
        $data = $this->getListLink($data);

        // _embedded
        $data = $this->getEmbeddedDisplay($data, $object);
        $data = $this->getEmbeddedBattery($data, $object);
        $data = $this->getEmbeddedCamera($data, $object);
        $data = $this->getEmbeddedStorage($data, $object);

        return $data;
    }

    public function getListLink(array $data): array
    {
        $data = $this->hateoas->setLink(
            $data,
            Smartphone::SHOW_PRODUCTS,
            null,
            'list'
        );

        return $data;
    }

    public function getEmbeddedDisplay(array $data, Smartphone $object): array
    {
        $data = $this->hateoas->setEmbedded($data, $object->getDisplay(), 'display', 'showDisplay');

        return $data;
    }

    public function getEmbeddedBattery(array $data, Smartphone $object): array
    {
        $data = $this->hateoas->setEmbedded($data, $object->getBattery(), 'battery', 'showBattery');

        return $data;
    }

    public function getEmbeddedCamera(array $data, Smartphone $object): array
    {
        $data = $this->hateoas->setEmbedded($data, $object->getCamera(), 'camera', 'showCamera');

        return $data;
    }

    public function getEmbeddedStorage(array $data, Smartphone $object): array
    {
        for ($i = 0; $i < count($object->getStorage()); $i++) {
            $data = $this->hateoas->setEmbedded($data, $object->getStorage()[$i], 'storage'  . '_' . $i, 'showStorage');
        }

        return $data;
    }
}
