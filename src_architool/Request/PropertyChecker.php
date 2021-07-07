<?php

declare(strict_types=1);

namespace ArchiTools\Request;

use App\Infrastructure\Exception\MissingRequestParameterException;
use JsonException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

class PropertyChecker
{
    /**
     * @throws ReflectionException
     * @throws MissingRequestParameterException
     * @throws JsonException
     */
    public static function checkProperties(string $argumentType, Request $request): void
    {
        $argumentTypeReflection = new ReflectionClass($argumentType);
        $properties = $argumentTypeReflection->getProperties();

        $params = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        foreach ($properties as $property) {
            $propertyCannotBeNull = !$property->getType()?->allowsNull();
            $propertyDoesNotExist = !array_key_exists($property->name, $params);

            if ($propertyDoesNotExist && $propertyCannotBeNull) {
                throw new MissingRequestParameterException($property->getName());
            }
        }
    }
}
