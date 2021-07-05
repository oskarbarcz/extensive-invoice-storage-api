<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\RemoveInvoiceCommand;
use App\Domain\Repository\InvoiceRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Uuid;

class RemoveInvoiceHandler implements MessageHandlerInterface
{
    private InvoiceRepository $repository;

    public function __construct(InvoiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RemoveInvoiceCommand $command): void
    {
        $id = Uuid::fromString($command->getId());
        $this->repository->remove($id);

        // TODO: remove also connected file
    }
}
