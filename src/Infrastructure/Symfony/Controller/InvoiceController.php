<?php

namespace App\Infrastructure\Symfony\Controller;

use App\Application\Command\CreateInvoiceCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends BaseController
{
    #[Route('api/v1/invoice')]
    public function create(CreateInvoiceCommand $command): JsonResponse
    {
        $this->handleCommand($command);

        return new JsonResponse('OK');
    }
}
