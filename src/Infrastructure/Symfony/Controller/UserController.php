<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Query\GetCurrentUserQuery;
use ArchiTools\Response\OpenApiResponse;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractCqrsAwareController
{
    #[Route(path: '/api/v1/users/me')]
    public function details(GetCurrentUserQuery $query): OpenApiResponse {
        $user = $query();
        $data = $user->toArray(false, ['password']);

        return OpenApiResponse::ok(null, $data);
    }
}
