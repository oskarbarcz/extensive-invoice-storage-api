<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class IsInvoiceType extends Constraint
{
    public $message = 'The string "{{ string }}" is not a valid Invoice Type.';
}
