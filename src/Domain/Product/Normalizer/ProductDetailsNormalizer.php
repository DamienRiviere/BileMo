<?php

namespace App\Domain\Product\Normalizer;

use App\Entity\Smartphone;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ProductDetailsNormalizer
 * @package App\Domain\Product\Normalizer
 */
final class ProductDetailsNormalizer implements ContextAwareNormalizerInterface
{

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /**
     * ProductDetailsNormalizer constructor.
     * @param ObjectNormalizer $normalizer
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(ObjectNormalizer $normalizer, UrlGeneratorInterface $urlGenerator)
    {
        $this->normalizer = $normalizer;
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
        return $data instanceof Smartphone && in_array('productDetails', $context['groups']);
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws ExceptionInterface
     */
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

    /**
     * @param array $data
     * @return array
     */
    public function getListLink(array $data): array
    {
        $data['_link']['list']['href'] = $this->urlGenerator->generate(Smartphone::SHOW_PRODUCTS);

        return $data;
    }

    /**
     * @param array $data
     * @param Smartphone $object
     * @return array
     * @throws ExceptionInterface
     */
    public function getEmbeddedDisplay(array $data, Smartphone $object): array
    {
        $data['_embedded']['display'] = $this->normalizer->normalize(
            $object->getDisplay(),
            'json',
            ['groups' => 'showDisplay']
        );

        return $data;
    }

    /**
     * @param array $data
     * @param Smartphone $object
     * @return array
     * @throws ExceptionInterface
     */
    public function getEmbeddedBattery(array $data, Smartphone $object): array
    {
        $data['_embedded']['battery'] = $this->normalizer->normalize(
            $object->getBattery(),
            'json',
            ['groups' => 'showBattery']
        );

        return $data;
    }

    /**
     * @param array $data
     * @param Smartphone $object
     * @return array
     * @throws ExceptionInterface
     */
    public function getEmbeddedCamera(array $data, Smartphone $object): array
    {
        $data['_embedded']['camera'] = $this->normalizer->normalize(
            $object->getCamera(),
            'json',
            ['groups' => 'showCamera']
        );

        return $data;
    }

    /**
     * @param array $data
     * @param Smartphone $object
     * @return array
     * @throws ExceptionInterface
     */
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
