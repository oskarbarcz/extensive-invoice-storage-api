<?php

declare(strict_types=1);

namespace App\Infrastructure\Filesystem;

use App\Domain\Invoice;
use App\Domain\Repository\FileRepository;
use Iterator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class FileStorage implements FileRepository
{
    private Finder $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public function findFileForInvoice(Invoice $invoice): SplFileInfo|null
    {
        $this->finder
            ->files()
            ->name($invoice->getFile());

        // catch potential multi-file problem
        if (!$this->finder->hasResults() || 1 !== $this->finder->count()) {
            return null;
        }

        return $this->firstOrNull($this->finder->getIterator());
    }

    private function firstOrNull(Iterator $iterator): SplFileInfo
    {
        // reset the iterator
        $iterator->rewind();

        // return first result
        return $iterator->current();
    }
}
