<?php

namespace App\Domain\Common\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class JWTListener
 * @package App\Domain\EventListener
 */
final class JWTListener
{

    /**
     * @param JWTInvalidEvent $event
     */
    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse(
            "Votre token est invalide, merci de vous réidentifier pour en obtenir un nouveau !",
            401
        );

        $event->setResponse($response);
    }

    /**
     * @param JWTNotFoundEvent $event
     */
    public function onJWTNotFound(JWTNotFoundEvent $event)
    {
        $data = [
            'status' => '401 Authentification nécessaire',
            'message' => 'Aucun token !'
        ];

        $response = new JsonResponse($data, 401);
        $event->setResponse($response);
    }

    /**
     * @param JWTExpiredEvent $event
     */
    public function onJWTExpired(JWTExpiredEvent $event)
    {
        $response = $event->getResponse();
        $response->setStatusCode(401);
        $response->setMessage("Votre token a expiré, veuillez vous réidentifier !");
    }
}
