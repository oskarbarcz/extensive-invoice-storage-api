<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Infrastructure\Exception\TranslatableException;
use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Throwable;

abstract class AbstractDomainException extends RuntimeException implements TranslatableException
{
    private bool $isTranslatable;

    #[Pure]
    public static function translatable(string $message, Throwable $previous = null):static
    {
        $self = new static($message, 0, $previous);
        $self->isTranslatable = true;

        return $self;
    }

    #[Pure]
    public static function nonTranslatable(string $message, Throwable $previous = null): static
    {
        $self = new static($message, 0, $previous);
        $self->isTranslatable = false;

        return $self;
    }

    public function isTranslatable(): bool
    {
        return $this->isTranslatable;
    }

    public function getTranslatableMessage(): string
    {
        return $this->message;
    }
}
