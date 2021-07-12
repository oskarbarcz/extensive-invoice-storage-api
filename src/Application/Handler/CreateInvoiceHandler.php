<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateInvoiceCommand;
use App\Domain\Invoice;
use App\Domain\Repository\InvoiceRepository;
use App\Domain\User;
use App\Domain\ValueObject\InvoiceType;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

class CreateInvoiceHandler implements MessageHandlerInterface
{
    private InvoiceRepository $invoiceRepository;
    private Security $security;

    public function __construct(InvoiceRepository $invoiceRepository, Security $security)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->security = $security;
    }

    public function __invoke(CreateInvoiceCommand $command): void
    {
        $type = new InvoiceType($command->getType());
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \RuntimeException('User is not logged in somehow.');
        }

        $invoice = new Invoice($command->getId(), $command->getName(), $type->toString(), $user);

        $this->invoiceRepository->add($invoice);
    }
}
