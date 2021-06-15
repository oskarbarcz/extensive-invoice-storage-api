<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Invoice;

interface InvoiceRepository
{
    public function add(Invoice $invoice): void;
}
