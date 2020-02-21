<?php

namespace App\Domain\Common\Subsriber;

use App\Domain\Common\Exception\AuthorizationException;
use App\Domain\Common\Exception\PageNotFoundException;
use App\Domain\Common\Exception\ValidationException;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionSubscriber
 * @package App\Domain\Common\Subsriber
 */
final class ExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onException(ExceptionEvent $event): void
    {
        $statusCode = 500;
        $message = [
            'message' => $event->getThrowable()->getMessage()
        ];

        switch (get_class($event->getThrowable())) {
            case PageNotFoundException::class:
            case EntityNotFoundException::class:
                $statusCode = 404;
                break;
            case AuthorizationException::class:
                $statusCode = 403;
                break;
            case ValidationException::class:
                $statusCode = 400;
                $message = $event->getThrowable()->getParams();
                break;
        }

        $event->setResponse(JsonResponder::response($message, $statusCode));
    }
}
