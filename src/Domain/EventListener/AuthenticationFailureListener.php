<?php

namespace App\Domain\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

/**
 * Class AuthenticationFailureListener
 * @package App\Domain\EventListener
 */
final class AuthenticationFailureListener
{

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $data = [
            'status' => '401 Non autorisé',
            'message' => 'Identifiants incorrects, veuillez entrer correctement votre email et votre mot de passe !'
        ];

        $response = new JWTAuthenticationFailureResponse($data);
        $event->setResponse($response);
    }
}
