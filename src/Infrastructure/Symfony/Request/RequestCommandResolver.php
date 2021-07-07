<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Request;

use ArchiTools\Request\AbstractRequestCommandResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Helper class for resolving command straightly from request body.
 *
 * This class just defines how do we check if we support the command. In this implementation we do this by checking
 * command namespace.
 */
final class RequestCommandResolver extends AbstractRequestCommandResolver
{
    private const SUPPORTED_COMMAND_NAMESPACE = 'App\\Application\\Command\\';

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return str_contains($argument->getType(), self::SUPPORTED_COMMAND_NAMESPACE);
    }
}
