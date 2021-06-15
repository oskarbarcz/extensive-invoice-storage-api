<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateInvoiceCommand;
use App\Domain\Invoice;
use App\Domain\Repository\InvoiceRepository;
use App\Domain\ValueObject\InvoiceType;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Uuid;

class CreateInvoiceHandler implements MessageHandlerInterface
{
    public function __construct(private InvoiceRepository $invoiceRepository)
    {
    }

    #[NoReturn]
    public function __invoke(CreateInvoiceCommand $command): void
    {
        $id = Uuid::v4();
        $type = new InvoiceType($command->getType());
        $invoice = new Invoice($id, $command->getName(), $type->toString());

        $this->invoiceRepository->add($invoice);

        dd($invoice);
    }
}
