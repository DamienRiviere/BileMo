<?php

namespace App\Domain\Services;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class SerializerService
 * @package App\Domain\Services
 */
final class SerializerService
{

    /** @var SerializerInterface  */
    protected $serializer;

    /**
     * SerializerService constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $data
     * @param array $groups
     * @return string
     */
    public function serializer($data, array $groups)
    {
        return $this->serializer->serialize($data, 'json', $groups);
    }
}
