<?php

declare(strict_types=1);

namespace ArchiTools\Request;


use App\Infrastructure\Exception\MissingRequestParameterException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

class PropertyChecker
{
    /** @throws ReflectionException|MissingRequestParameterException */
    public static function checkProperties(string $argumentType, Request $request): void
    {
        $argumentTypeReflection = new ReflectionClass($argumentType);
        $properties = $argumentTypeReflection->getProperties();

        foreach ($properties as $property) {
            if (self::isParameterMissing($property, $request->getContent())) {
                throw new MissingRequestParameterException($property->getName());
            }
        }
    }

    private static function isParameterMissing(ReflectionProperty $property, string $content): bool
    {
        $propertyCannotBeNull = !$property->getType()?->allowsNull();
        $propertyDoesNotExist = str_contains($content, sprintf('"%s"', $property->getName()));

        return $propertyDoesNotExist && $propertyCannotBeNull;
    }

}
