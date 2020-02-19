<?php

namespace App\Domain\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HttpCache
{

    public function setHttpCache(Response $response, Request $request, int $maxAge = null): Response
    {
        $response = $this->setExpirationCache($response, $maxAge);
        $response = $this->setValidationCache($response, $request);

        return $response;
    }

    public function setExpirationCache(Response $response, int $maxAge): Response
    {
        $response->setMaxAge($maxAge);
        $response->setSharedMaxAge($maxAge);

        return $response;
    }

    public function setValidationCache(Response $response, Request $request): Response
    {
        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($request);

        return $response;
    }
}
