<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Repository\InvoiceRepository;
use App\Domain\User;
use RuntimeException;
use Symfony\Component\Security\Core\Security;

final class GetInvoicesByMonthQuery
{
    private InvoiceRepository $repository;
    private Security $security;

    public function __construct(InvoiceRepository $repository, Security $security)
    {
        $this->repository = $repository;
        $this->security = $security;
    }

    public function __invoke(int $month, int $year): array
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            throw new RuntimeException('exception.user.not_logged');
        }

        return $this->repository->getByMonthAndOwner($month, $year, $currentUser);
    }
}
