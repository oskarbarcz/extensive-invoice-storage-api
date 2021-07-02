<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Invoice;
use App\Infrastructure\Symfony\Request\UploadedBase64File;
use Symfony\Component\Mime\MimeTypes;

class InvoiceFilenameGenerator
{
    public static function getFileName(UploadedBase64File $file, Invoice $invoice): string
    {
        $mt = new MimeTypes();

        $mimeType = $file->getMimeType();
        $possibleExtensions = $mt->getExtensions($mimeType);
        $extention = $possibleExtensions[0];

        $fileName = $invoice->getId()->toRfc4122();

        return "{$fileName}.{$extention}";
    }
}
