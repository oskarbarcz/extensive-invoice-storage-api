<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TranslateOpenApiResponseListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
