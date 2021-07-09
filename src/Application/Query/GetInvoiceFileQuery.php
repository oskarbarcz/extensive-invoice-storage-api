<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Repository\FileRepository;
use App\Domain\Repository\InvoiceRepository;
use Symfony\Component\Uid\Uuid;

final class GetInvoiceFileQuery
{
    private FileRepository $fileRepository;
    private InvoiceRepository $invoiceRepository;

    public function __construct(FileRepository $fileRepository, InvoiceRepository $invoiceRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function __invoke(string $id)
    {
        $uuid = Uuid::fromString($id);

        $invoice = $this->invoiceRepository->getByIdAndUser($uuid);

        return $this->fileRepository->findFileForInvoice($invoice);
    }
}
