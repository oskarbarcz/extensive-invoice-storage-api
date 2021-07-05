<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventListener;

use App\Infrastructure\Exception\TranslatableException;
use ArchiTools\Response\OpenApiResponse;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

final class GeneralApiExceptionListener implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    private const EXCEPTION_TRANSLATION_DOMAIN = 'exception';
    private array $returnCodeMap;

    public function __construct(TranslatorInterface $translator, array $returnCodeMap)
    {
        $this->translator = $translator;
        $this->returnCodeMap = $returnCodeMap;
    }

    #[ArrayShape([KernelEvents::EXCEPTION => "\string[][]"])]
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => [['exceptionToJson']]];
    }

    public function exceptionToJson(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        // we're not handling exceptions that cannot be translated
        if (!$throwable instanceof TranslatableException) {
            return;
        }

        $message = $throwable->isTranslatable() ? $this->translate($throwable->getMessage()) : $throwable->getMessage();
        $code = $this->assignErrorCode($throwable);

        $response = OpenApiResponse::exception($message, $code);

        $event->allowCustomResponseCode();
        $event->setResponse($response);
    }

    private function translate(string $message): string
    {
        return $this->translator->trans($message, [], self::EXCEPTION_TRANSLATION_DOMAIN);
    }

    private function assignErrorCode (Throwable $throwable): int
    {
        $errorClass = get_class($throwable);

        // every error not configured to return other code will return a 500
        $isErrorCodeCustomized = array_key_exists($errorClass, $this->returnCodeMap);
        return $isErrorCodeCustomized ? $this->returnCodeMap[$errorClass]: OpenApiResponse::HTTP_INTERNAL_SERVER_ERROR;
    }
}
