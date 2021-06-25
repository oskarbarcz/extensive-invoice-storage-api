<?php

declare(strict_types=1);

namespace ArchiTools\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OpenApiResponse extends JsonResponse
{
    private function __construct(
        mixed $data = null,
        string | null $message = null,
        int $status = self::HTTP_OK,
        array $headers = []
    ) {
        $content = [
            'code' => $status,
            'message' => Response::$statusTexts[$status],
            'details' => $message,
            'data' => $data,
        ];

        parent::__construct($content, $status, $headers, false);
    }

    public static function empty(string | null $message = null, int $status = Response::HTTP_NO_CONTENT): self
    {
        if (null === $message) {
            $message = Response::$statusTexts[$status];
        }

        return new self(null, $message, $status);
    }

    public static function created(mixed $id, string $message): self
    {
        return new self(['id' => $id], $message, self::HTTP_CREATED);
    }

    public static function collection(array $resources, int $status = self::HTTP_OK): self
    {
        return new self($resources, null, $status);
    }

    public static function item(mixed $item, int $status): self
    {
        return new self($item, null, $status);
    }
}
