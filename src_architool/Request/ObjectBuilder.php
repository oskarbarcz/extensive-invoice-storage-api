<?php

declare(strict_types=1);

namespace ArchiTools\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class ObjectBuilder
{
    /**
     * @throws \JsonException
     */
    public static function buildFromRequest(Request $request): object
    {
        $routeParams = $request->attributes->get('_route_params');
        $content = $request->getContent();

        $requestIsNotEmpty = $content !== null || $content !== [];

        if ($requestIsNotEmpty) {
            $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
            $content->id = $routeParams['id'] ?? $routeParams['uuid'] ?? null;

            return $content;
        }

        $content = (object)['id' => $routeParams['id'] ?? $routeParams['uuid'] ?? null];

        $typeIsDefined = array_key_exists('type', $routeParams) && $routeParams['type'] !== null;

        if ($typeIsDefined) {
            $content->type = $routeParams['type'];
        }

        return $content;
    }
}
