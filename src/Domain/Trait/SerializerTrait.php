<?php

declare(strict_types=1);

namespace App\Domain\Trait;

trait SerializerTrait
{
    public function toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            $array[$key] = $value;
        }

        return $array;
    }
}
