<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Repository\InvoiceRepository;

final class GetInvoicesByMonthQuery
{
    private InvoiceRepository $repository;

    public function __construct(InvoiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $month, int $year): array
    {
        $invoices = $this->repository->getByMonth($month, $year);

        return $invoices;
    }
}
