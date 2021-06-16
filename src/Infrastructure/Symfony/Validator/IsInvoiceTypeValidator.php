<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Validator;

use App\Domain\Exception\ValueObjectException;
use App\Domain\ValueObject\InvoiceType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsInvoiceTypeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsInvoiceType) {
            throw new UnexpectedTypeException($constraint, IsInvoiceType::class);
        }

        try {
            new InvoiceType($value);
        } catch (ValueObjectException $e) {
            $this->context->buildViolation($constraint->message)
                          ->setParameter('{{ string }}', $value)
                          ->addViolation();
        }
    }
}
