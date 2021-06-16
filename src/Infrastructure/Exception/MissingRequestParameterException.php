<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

class MissingRequestParameterException extends Exception
{
    #[Pure]
    public function __construct(
        string $parameter
    ) {
        parent::__construct(sprintf('Parameter `%s` is missing', $parameter));
    }
}
