<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\UploadInvoiceFileCommand;
use App\Application\Query\GetInvoiceFileQuery;
use ArchiTools\Response\OpenApiResponse;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceFileController extends AbstractCqrsAwareController
{
    #[Route(
        path: 'api/invoice-file/{id}',
        name: 'api_invoice-file_upload',
        methods: ['POST']
    )]
    public function uploadInvoiceFile(UploadInvoiceFileCommand $command): OpenApiResponse
    {
        $this->handleCommand($command);

        return OpenApiResponse::ok('File upload OK.');
    }

    #[Route(
        path: 'api/invoice-file/{id}',
        name: 'api_invoice_download',
        methods: ['GET']
    )]
    public function downloadInvoiceFile(GetInvoiceFileQuery $query, string $id): OpenApiResponse
    {
        $file = $query($id);

        if (null === $file) {
            return OpenApiResponse::notFound('response.invoice_file.not_found');
        }

        $mimeTypeGuesser = new MimeTypes();
        $fileBaseEncodedContent = base64_encode($file->getContents());

        $data = [
            'mime_type' => $mimeTypeGuesser->guessMimeType($file->getPathname()),
            'file' => $fileBaseEncodedContent,
        ];

        return OpenApiResponse::ok(null, $data);
    }
}
