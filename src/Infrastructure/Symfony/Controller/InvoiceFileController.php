<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\UploadInvoiceFileCommand;
use ArchiTools\Response\OpenApiResponse;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceFileController extends AbstractCqrsAwareController
{
    #[Route(
        path: 'api/invoice-file/{id}',
        name: 'api_invoice_file-upload',
        methods: ['POST']
    )]
    public function uploadInvoiceFile(UploadInvoiceFileCommand $command): OpenApiResponse
    {
        $this->handleCommand($command);

        return OpenApiResponse::ok('File upload OK.');
    }

    public function downloadInvoiceFile(): OpenApiResponse
    {

    }
}
