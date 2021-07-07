<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventListener;

use App\Infrastructure\Exception\TranslatableException;
use ArchiTools\Response\OpenApiResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class GeneralApiExceptionListener implements EventSubscriberInterface
{
    private array $returnCodeMap;

    public function __construct(array $returnCodeMap)
    {
        $this->returnCodeMap = $returnCodeMap;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['onKernelException']];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        // we're not handling exceptions that cannot be translated
        if (!$throwable instanceof TranslatableException) {
            return;
        }

        $code = $this->assignErrorCode($throwable);
        $response = OpenApiResponse::exception($throwable->getMessage(), $code);

        $event->allowCustomResponseCode();
        $event->setResponse($response);
    }

    private function assignErrorCode(Throwable $throwable): int
    {
        $errorClass = get_class($throwable);

        // every error not configured to return other code will return a 500
        $isErrorCodeCustomized = array_key_exists($errorClass, $this->returnCodeMap);

        return $isErrorCodeCustomized ? $this->returnCodeMap[$errorClass] : OpenApiResponse::HTTP_INTERNAL_SERVER_ERROR;
    }
}
