<?php

namespace App\Infrastructure\Filesystem;

use App\Domain\Invoice;
use App\Domain\Repository\FileRepository;
use Iterator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class FileStorage implements FileRepository
{
    private string $invoiceFilesPath;

    public function __construct(string $invoiceFilesPath)
    {
        $this->invoiceFilesPath = $invoiceFilesPath;
    }

    public function findFileForInvoice(Invoice $invoice): SplFileInfo | null
    {
        // not autowired because it stores global state

        $finder = new Finder();
        $finder
            ->files()
            ->in($this->invoiceFilesPath)
        ->name($invoice->getFile());

        // catch potential multi-file problem
        if (!$finder->hasResults() || 1 !== $finder->count()) {
            return null;
        }

        return $this->firstOrNull($finder->getIterator());
    }

    private function firstOrNull(Iterator $iterator): SplFileInfo
    {
        // reset the iterator
        $iterator->rewind();

        // return first result
        return $iterator->current();
    }
}
