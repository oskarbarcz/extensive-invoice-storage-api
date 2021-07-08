<?php

declare(strict_types=1);

namespace App\Domain\Trait;

/**
 * Contains method to self-serialize object without password field
 */
trait PasswordAwareSerializerTrait
{
    public function toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            if ($key === 'password') {
                continue;
            }

            $array[$key] = $value;
        }

        return $array;
    }
}
