<?php

namespace App\Domain\Product\Normalizer;

use App\Domain\Services\Hateoas;
use App\Domain\Services\Pagination;
use App\Entity\Smartphone;
use App\Repository\SmartphoneRepository;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ProductsNormalizer implements ContextAwareNormalizerInterface
{

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var SmartphoneRepository */
    protected $smartphoneRepo;

    /** @var Hateoas */
    protected $hateoas;

    public function __construct(
        ObjectNormalizer $normalizer,
        SmartphoneRepository $smartphoneRepo,
        Hateoas $hateoas
    ) {
        $this->normalizer = $normalizer;
        $this->smartphoneRepo = $smartphoneRepo;
        $this->hateoas = $hateoas;
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
        $data = $this->getFirstPageLink($data, $pagination);
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
        $data = $this->hateoas->setLink(
            $data,
            Smartphone::SHOW_PRODUCT_DETAILS,
            ['id' => $object->getId()],
            'self'
        );

        return $data;
    }

    public function getFirstPageLink(array $data, Pagination $pagination): array
    {
        $data = $this->hateoas->setLink(
            $data,
            Smartphone::SHOW_PRODUCTS,
            ['page' => $pagination->getFirstPage()],
            'first'
        );

        return $data;
    }

    public function getLastPageLink(array $data, Pagination $pagination): array
    {
        $data = $this->hateoas->setLink(
            $data,
            Smartphone::SHOW_PRODUCTS,
            ['page' => $pagination->getLastPage()],
            'last'
        );

        return $data;
    }

    public function getNextPageLink(array $data, Pagination $pagination): array
    {
        $data = $this->hateoas->setLink(
            $data,
            Smartphone::SHOW_PRODUCTS,
            ['page' => $pagination->getNextPage()],
            'next'
        );

        return $data;
    }

    public function getPreviousPageLink(array $data, Pagination $pagination): array
    {
        $data = $this->hateoas->setLink(
            $data,
            Smartphone::SHOW_PRODUCTS,
            ['page' => $pagination->getPreviousPage()],
            'prev'
        );

        return $data;
    }

    public function getEmbeddedDisplay(array $data, Smartphone $object): array
    {
        $data = $this->hateoas->setEmbedded($data, $object->getDisplay(), 'display', 'display');

        return $data;
    }

    public function getEmbeddedBattery(array $data, Smartphone $object): array
    {
        $data = $this->hateoas->setEmbedded($data, $object->getBattery(), 'battery', 'battery');

        return $data;
    }

    public function getEmbeddedCamera(array $data, Smartphone $object): array
    {
        $data = $this->hateoas->setEmbedded($data, $object->getCamera(), 'camera', 'camera');

        return $data;
    }

    public function getEmbeddedStorage(array $data, Smartphone $object): array
    {
        $data = $this->hateoas->setEmbedded($data, $object->getStorage(), 'storage', 'storage');

        return $data;
    }
}
