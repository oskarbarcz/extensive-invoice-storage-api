<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\CreateInvoiceCommand;
use App\Application\Command\RemoveInvoiceCommand;
use App\Application\Query\GetInvoiceByIdQuery;
use App\Application\Query\GetInvoicesByMonthQuery;
use ArchiTools\Response\OpenApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class InvoiceController extends AbstractCqrsAwareController
{
    #[Route(
        path: 'api/v1/invoices',
        name: 'api_invoice_create',
        methods: ['POST']
    )]
    public function create(CreateInvoiceCommand $command): JsonResponse
    {
        $this->handleCommand($command);

        return OpenApiResponse::created($command->getId(), 'Invoice was successfully created.');
    }

    #[Route(
        path: 'api/v1/invoices/by-month/{year}/{month}',
        name: 'api_invoice_get-by-month',
        methods: ['GET']
    )]
    public function getByMonth(
        GetInvoicesByMonthQuery $query, int $month, int $year
    ): JsonResponse {
        $invoices = $query($month, $year);
        $status = [] === $invoices ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return OpenApiResponse::collection($invoices, $status);
    }

    #[Route(
        path: 'api/v1/invoices/{id}',
        name: 'api_invoice_get-by-id',
        methods: ['GET']
    )]
    public function getInvoiceById(
        GetInvoiceByIdQuery $query, string $id
    ) {
        $invoice = $query(Uuid::fromString($id));

        if (null === $invoice) {
            return OpenApiResponse::notFound('Invoice were not found.');
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
    ): JsonResponse {
        $this->handleCommand($command);

        return OpenApiResponse::empty();
    }
}
