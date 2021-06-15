<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final class InvoiceType
{
    private const COST = 'cost';
    private const REVENUE = 'revenue';

    private const ALLOWED_TYPES = [self::COST, self::REVENUE];

    private string $type;

    public function __construct(string $string)
    {
        if (!in_array($string, self::ALLOWED_TYPES, true)) {
            throw new RuntimeException("Invalid invoice type");
        }

        $this->type = $string;
    }

    public function toString(): string
    {
        return $this->type;
    }
}
