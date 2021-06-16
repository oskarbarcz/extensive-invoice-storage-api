<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Invoice;
use Symfony\Component\Uid\Uuid;

interface InvoiceRepository
{
    public function add(Invoice $invoice): void;

    public function getByMonth(int $month, int $year);

    public function remove(Uuid $getId);
}
