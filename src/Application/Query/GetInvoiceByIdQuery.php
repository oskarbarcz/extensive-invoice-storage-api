<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Exception\AccessRestrictedException;
use App\Domain\Invoice;
use App\Domain\Repository\InvoiceRepository;
use App\Domain\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

final class GetInvoiceByIdQuery
{
    private InvoiceRepository $repository;
    private Security $security;

    public function __construct(InvoiceRepository $repository, Security $security)
    {
        $this->repository = $repository;
        $this->security = $security;
    }

    public function __invoke(Uuid $id): Invoice|null
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            return null;
        }

        return $this->repository->getByIdAndUser($id, $currentUser);
    }
}
