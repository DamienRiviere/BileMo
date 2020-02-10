<?php

namespace App\Domain\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class Hateoas
{

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var ObjectNormalizer */
    protected $normalizer;

    public function __construct(UrlGeneratorInterface $urlGenerator, ObjectNormalizer $normalizer)
    {
        $this->urlGenerator = $urlGenerator;
        $this->normalizer = $normalizer;
    }

    /**
     * @param array $data
     * @param string $route
     * @param array $params
     * @param string $fieldName
     * @return array
     */
    public function setLink(array $data, string $route, ?array $params, string $fieldName): array
    {
        if (is_null($params)) {
            $data['_link'][$fieldName]['href'] = $this->urlGenerator->generate(
                $route
            );

            return $data;
        }

        $data['_link'][$fieldName]['href'] = $this->urlGenerator->generate(
            $route,
            $params
        );

        return $data;
    }

    public function setEmbedded($data, $object, string $fieldName, string $groupsName): array
    {
        $data['_embedded'][$fieldName] = $this->normalizer->normalize(
            $object,
            'json',
            ['groups' => $groupsName]
        );

        return $data;
    }
}
