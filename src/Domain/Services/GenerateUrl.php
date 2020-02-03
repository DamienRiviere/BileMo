<?php

namespace App\Domain\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class GenerateUrl
{

    /** @var UrlGeneratorInterface */
    protected $url;

    public function __construct(UrlGeneratorInterface $url)
    {
        $this->url = $url;
    }

    public function generateHeader(string $route, array $params = null)
    {
        return [ "Location" => $this->url->generate($route, $params)];
    }
}
