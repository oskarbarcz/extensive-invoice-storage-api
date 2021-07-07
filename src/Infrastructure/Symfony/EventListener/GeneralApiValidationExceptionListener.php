<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventListener;

use ArchiTools\Exception\ValidationFailedException;
use ArchiTools\Response\OpenApiResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class GeneralApiValidationExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => [['onKernelException']]];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        // we're handling here only validation exception
        if (!$throwable instanceof ValidationFailedException) {
            return;
        }

        $response = OpenApiResponse::validationFail($throwable->getConstraintValidations());

        $event->allowCustomResponseCode();
        $event->setResponse($response);
    }
}
