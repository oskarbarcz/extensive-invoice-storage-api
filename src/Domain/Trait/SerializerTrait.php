<?php

declare(strict_types=1);

namespace App\Domain\Trait;

use App\Infrastructure\Doctrine\Entity\SerializableReadModel;

/**
 * Contains method to self-serialize object
 */
trait SerializerTrait
{
    public function toArray($recursive = false, array $withoutFields = []): array
    {
        $properties = [];

        foreach ($this as $key => $value) {
            if (in_array($key, $withoutFields)) {
                continue;
            }

            if (!$recursive && $value instanceof SerializableReadModel) {
                continue;
            }

            $properties[$key] = $value;
        }

        return $properties;
    }
}
