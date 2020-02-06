<?php

namespace App\Domain\Product\Normalizer;

use App\Entity\Smartphone;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ProductsNormalizer implements ContextAwareNormalizerInterface
{

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var ObjectNormalizer */
    protected $normalizer;

    public function __construct(UrlGeneratorInterface $urlGenerator, ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Smartphone && in_array('listProduct', $context['groups']);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['_link']['self']['href'] = $this->urlGenerator->generate(
            'api_products_details',
            ['id' => $object->getId()]
        );

        $data['_embedded']['display'] = $this->normalizer->normalize(
            $object->getDisplay(),
            $format,
            ['groups' => ['display']]
        );

        $data['_embedded']['battery'] = $this->normalizer->normalize(
            $object->getBattery(),
            $format,
            ['groups' => ['battery']]
        );

        $data['_embedded']['camera'] = $this->normalizer->normalize(
            $object->getCamera(),
            $format,
            ['groups' => ['camera']]
        );

        $data['_embedded']['storage'] = $this->normalizer->normalize(
            $object->getStorage(),
            $format,
            ['groups' => ['storage']]
        );

        return $data;
    }
}
