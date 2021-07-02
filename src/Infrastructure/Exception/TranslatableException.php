<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Throwable;

interface TranslatableException
{
    public function isTranslatable(): bool;

    public function getTranslatableMessage(): string;

    public static function translatable(string $message, Throwable $previous = null): static;

    public static function nonTranslatable(string $message, Throwable $previous = null):static;
}
