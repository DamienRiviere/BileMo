<?php

namespace App\Domain\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class GenerateUrl
 * @package App\Domain\Services
 */
final class GenerateUrl
{

    /** @var UrlGeneratorInterface */
    protected $url;

    /**
     * GenerateUrl constructor.
     * @param UrlGeneratorInterface $url
     */
    public function __construct(UrlGeneratorInterface $url)
    {
        $this->url = $url;
    }

    /**
     * @param string $route
     * @param array|null $params
     * @return array
     */
    public function generateHeader(string $route, array $params = null)
    {
        return [ "Location" => $this->url->generate($route, $params)];
    }
}
