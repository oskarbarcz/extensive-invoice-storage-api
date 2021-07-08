<?php

declare(strict_types=1);

namespace App\Domain\Trait;

/**
 * Contains method to self-serialize object
 *
 * @see PasswordAwareSerializerTrait if using field for password
 */
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
