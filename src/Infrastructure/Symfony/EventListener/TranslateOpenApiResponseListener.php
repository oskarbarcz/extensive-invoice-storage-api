<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventListener;

use ArchiTools\Response\OpenApiResponse;
use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TranslateOpenApiResponseListener implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    private const EXCEPTION_TRANSLATION_DOMAIN = 'exception';
    private const RESPONSE_TRANSLATION_DOMAIN = 'response';

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE =>['translateResponse']];
    }

    /** @throws JsonException */
    public function translateResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        // we're not handling
        if(!$response instanceof OpenApiResponse){
            return;
        }

        $domain = $response->isError() ? self::EXCEPTION_TRANSLATION_DOMAIN : self::RESPONSE_TRANSLATION_DOMAIN;

        $originalContent = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $originalContent['details'] = $this->translate($originalContent['details'], $domain);

        $response->setContent(json_encode($originalContent, JSON_THROW_ON_ERROR));
    }

    private function translate(string $message, string $domain): string
    {
        return $this->translator->trans($message, [], $domain);
    }
}
