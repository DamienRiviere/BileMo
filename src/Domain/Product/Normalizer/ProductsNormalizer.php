<?php

namespace App\Domain\Product\Normalizer;

use App\Domain\Services\Pagination;
use App\Entity\Smartphone;
use App\Repository\SmartphoneRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ProductsNormalizer implements ContextAwareNormalizerInterface
{

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var SmartphoneRepository */
    protected $smartphoneRepo;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    public function __construct(
        ObjectNormalizer $normalizer,
        SmartphoneRepository $smartphoneRepo,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->normalizer = $normalizer;
        $this->smartphoneRepo = $smartphoneRepo;
        $this->urlGenerator = $urlGenerator;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Smartphone && in_array('listProduct', $context['groups']);
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $pagination = new Pagination(
            Smartphone::LIMIT_PER_PAGE,
            $this->smartphoneRepo->findAll(),
            $context['page']
        );

        // _link
        $data = $this->getSelfLink($data, $object);
        $data = $this->getFirstPageLink($data);
        $data = $this->getLastPageLink($data, $pagination);

        if ($context['page'] < $pagination->getPages()) {
            $data = $this->getNextPageLink($data, $pagination);
        }

        if ($context['page'] >= 2) {
            $data = $this->getPreviousPageLink($data, $pagination);
        }

        // _embedded
        $data = $this->getEmbeddedDisplay($data, $object);
        $data = $this->getEmbeddedBattery($data, $object);
        $data = $this->getEmbeddedCamera($data, $object);
        $data = $this->getEmbeddedStorage($data, $object);

        return $data;
    }

    public function getSelfLink(array $data, Smartphone $object): array
    {
        $data['_link']['self']['href'] = $this->urlGenerator->generate(
            Smartphone::SHOW_PRODUCT_DETAILS,
            ['id' => $object->getId()]
        );

        return $data;
    }

    public function getFirstPageLink(array $data): array
    {
        $data['_link']['first']['href'] = $this->urlGenerator->generate(
            Smartphone::SHOW_PRODUCTS,
            ['page' => 1]
        );

        return $data;
    }

    public function getLastPageLink(array $data, Pagination $pagination): array
    {
        $data['_link']['last']['href'] = $this->urlGenerator->generate(
            Smartphone::SHOW_PRODUCTS,
            ['page' => $pagination->getLastPage()]
        );

        return $data;
    }

    public function getNextPageLink(array $data, Pagination $pagination): array
    {
        $data['_link']['next']['href'] = $this->urlGenerator->generate(
            Smartphone::SHOW_PRODUCTS,
            ['page' => $pagination->getNextPage()]
        );

        return $data;
    }

    public function getPreviousPageLink(array $data, Pagination $pagination): array
    {
        $data['_link']['prev']['href'] = $this->urlGenerator->generate(
            Smartphone::SHOW_PRODUCTS,
            ['page' => $pagination->getPreviousPage()]
        );

        return $data;
    }

    public function getEmbeddedDisplay(array $data, Smartphone $object): array
    {
        $data['_embedded']['display'] = $this->normalizer->normalize(
            $object->getDisplay(),
            'json',
            ['groups' => 'display']
        );

        return $data;
    }

    public function getEmbeddedBattery(array $data, Smartphone $object): array
    {
        $data['_embedded']['battery'] = $this->normalizer->normalize(
            $object->getBattery(),
            'json',
            ['groups' => 'battery']
        );

        return $data;
    }

    public function getEmbeddedCamera(array $data, Smartphone $object): array
    {
        $data['_embedded']['camera'] = $this->normalizer->normalize(
            $object->getCamera(),
            'json',
            ['groups' => 'camera']
        );

        return $data;
    }

    public function getEmbeddedStorage(array $data, Smartphone $object): array
    {
        $data['_embedded']['storage'] = $this->normalizer->normalize(
            $object->getStorage(),
            'json',
            ['groups' => 'storage']
        );

        return $data;
    }
}
