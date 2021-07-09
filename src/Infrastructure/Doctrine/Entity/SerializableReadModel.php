<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Entity;

interface SerializableReadModel
{
    public function toArray($recursive = false, array $withoutFields = []): array;
}