<?php

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\CreateInvoiceCommand;
use App\Application\Command\RemoveInvoiceCommand;
use App\Application\Query\GetInvoicesByMonthQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class InvoiceController extends BaseController
{
    #[Route('api/v1/invoices', methods: ['POST'])]
    public function create(CreateInvoiceCommand $command): JsonResponse
    {
        $this->handleCommand($command);

        return new JsonResponse('OK', Response::HTTP_OK);
    }

    #[Route('api/v1/invoices/by-month/{year}/{month}', methods: ['GET'])]
    public function getByMonth(GetInvoicesByMonthQuery $query, int $month, int $year): JsonResponse
    {
        $invoices = $query($month, $year);
        $status = $invoices ===[] ?Response::HTTP_NO_CONTENT:Response::HTTP_OK;

        return new JsonResponse($invoices, $status);
    }

    #[Route('api/v1/invoices/{id}', methods: ['DELETE'])]
    public function remove(RemoveInvoiceCommand $command): JsonResponse
    {
        $this->handleCommand($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
