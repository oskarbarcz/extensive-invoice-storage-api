<?php

declare(strict_types=1);

namespace App\Application\Command;

final class UploadInvoiceFileCommand
{
    private string|null $id;
    private string $file;

    public function __construct(string|null $id, string $file)
    {
        $this->id = $id;
        $this->file = $file;
    }

    public function getId(): string|null
    {
        return $this->id;
    }

    public function getFile(): string
    {
        return $this->file;
    }
}
