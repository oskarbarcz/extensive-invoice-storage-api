<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\CreateInvoiceCommand;
use App\Application\Command\RemoveInvoiceCommand;
use App\Application\Query\GetInvoiceByIdQuery;
use App\Application\Query\GetInvoicesByMonthQuery;
use App\Domain\Invoice;
use ArchiTools\Response\OpenApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class InvoiceController extends AbstractCqrsAwareController
{
    #[Route(
        path: 'api/v1/invoices',
        name: 'api_invoice_create',
        methods: ['POST']
    )]
    public function create(
        CreateInvoiceCommand $command
    ): OpenApiResponse {
        $this->handleCommand($command);

        return OpenApiResponse::created($command->getId(), 'response.invoice.created');
    }

    #[Route(
        path: 'api/v1/invoices/by-month/{year}/{month}',
        name: 'api_invoice_get-by-month',
        methods: ['GET']
    )]
    public function getByMonth(
        GetInvoicesByMonthQuery $query,
        int $month,
        int $year
    ): OpenApiResponse {
        $invoices = $query($month, $year);
        $array = array_map(fn(Invoice $i) => $i->toArray(), $invoices);

        $status = [] === $invoices ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return OpenApiResponse::collection($array, $status);
    }

    #[Route(
        path: 'api/v1/invoices/{id}',
        name: 'api_invoice_get-by-id',
        methods: ['GET']
    )]
    public function getInvoiceById(
        GetInvoiceByIdQuery $query,
        string $id
    ): OpenApiResponse {
        $invoice = $query(Uuid::fromString($id));

        if (null === $invoice) {
            return OpenApiResponse::notFound('response.invoice.not_found');
        }

        return OpenApiResponse::item($invoice->toArray());
    }

    #[Route(
        path: 'api/v1/invoices/{id}',
        name: 'api_invoice_remove',
        methods: ['DELETE']
    )]
    public function remove(
        RemoveInvoiceCommand $command
    ): OpenApiResponse {
        $this->handleCommand($command);

        return OpenApiResponse::empty();
    }
}
