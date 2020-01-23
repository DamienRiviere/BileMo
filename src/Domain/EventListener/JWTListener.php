<?php

namespace App\Domain\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

final class JWTListener
{

    /**
     * @param JWTInvalidEvent $event
     */
    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse(
            "Votre token est invalide, merci de vous réidentifier pour en obtenir un nouveau",
            403
        );

        $event->setResponse($response);
    }

    /**
     * @param JWTNotFoundEvent $event
     */
    public function onJWTNotFound(JWTNotFoundEvent $event)
    {
        $data = [
            'status' => '403 Interdit',
            'message' => 'Aucun token'
        ];

        $response = new JsonResponse($data, 403);
        $event->setResponse($response);
    }

    /**
     * @param JWTExpiredEvent $event
     */
    public function onJWTExpired(JWTExpiredEvent $event)
    {
        $response = $event->getResponse();
        $response->setMessage("Votre token a expiré, veuillez vous réidentifier");
    }
}
