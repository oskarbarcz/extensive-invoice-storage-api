<?php

declare(strict_types=1);

namespace App\Application\Query;


use App\Domain\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class GetCurrentUserQuery
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(): User|UserInterface
    {
        return $this->security->getUser();
    }
}
