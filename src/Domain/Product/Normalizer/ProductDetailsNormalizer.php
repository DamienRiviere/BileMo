<?php

namespace App\Domain\Product\Normalizer;

use App\Entity\Smartphone;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ProductDetailsNormalizer implements ContextAwareNormalizerInterface
{

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    public function __construct(ObjectNormalizer $normalizer, UrlGeneratorInterface $urlGenerator)
    {
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
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
        $data['_link']['list']['href'] = $this->urlGenerator->generate(Smartphone::SHOW_PRODUCTS);

        return $data;
    }

    public function getEmbeddedDisplay(array $data, Smartphone $object): array
    {
        $data['_embedded']['display'] = $this->normalizer->normalize(
            $object->getDisplay(),
            'json',
            ['groups' => 'showDisplay']
        );

        return $data;
    }

    public function getEmbeddedBattery(array $data, Smartphone $object): array
    {
        $data['_embedded']['battery'] = $this->normalizer->normalize(
            $object->getBattery(),
            'json',
            ['groups' => 'showBattery']
        );

        return $data;
    }

    public function getEmbeddedCamera(array $data, Smartphone $object): array
    {
        $data['_embedded']['camera'] = $this->normalizer->normalize(
            $object->getCamera(),
            'json',
            ['groups' => 'showCamera']
        );

        return $data;
    }

    public function getEmbeddedStorage(array $data, Smartphone $object): array
    {
        for ($i = 0; $i < count($object->getStorage()); $i++) {
            $data['_embedded']['storage' . '_' . $i] = $this->normalizer->normalize(
                $object->getStorage()[$i],
                'json',
                ['groups' => 'showStorage']
            );
        }

        return $data;
    }
}
