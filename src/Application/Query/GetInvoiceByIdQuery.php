<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Invoice;
use App\Domain\Repository\InvoiceRepository;
use Symfony\Component\Uid\Uuid;

final class GetInvoiceByIdQuery
{
    public function __construct(private InvoiceRepository $repository)
    {
    }

    public function __invoke(Uuid $id): Invoice | null
    {
        return $this->repository->getById($id);
    }
}
