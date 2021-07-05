<?php

namespace App\Domain\Repository;

use App\Domain\Invoice;

interface FileRepository
{
    public function findFileForInvoice(Invoice $invoice);
}
