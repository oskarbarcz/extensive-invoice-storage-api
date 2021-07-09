<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\UploadInvoiceFileCommand;
use App\Domain\Exception\DomainLogicException;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\InvoiceRepository;
use App\Domain\Service\InvoiceFilenameGenerator;
use App\Infrastructure\Symfony\Request\UploadedBase64File;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Uuid;

class UploadInvoiceFileHandler implements MessageHandlerInterface
{
    private string $uploadFilePath;
    private InvoiceRepository $repository;

    public function __construct(InvoiceRepository $repository, string $uploadFilePath)
    {
        $this->uploadFilePath = $uploadFilePath;
        $this->repository = $repository;
    }

    public function __invoke(UploadInvoiceFileCommand $command): void
    {
        $uuid = Uuid::fromString($command->getId());
        $invoice = $this->repository->getByIdAndUser($uuid);

        if (null === $invoice) {
            throw NotFoundException::translatable('exception.invoice.not_found');
        }

        if (null !== $invoice->getFile()) {
            throw DomainLogicException::translatable('exception.invoice.file_already_set');
        }

        $file = new UploadedBase64File($command->getFile(), '');
        $fileName = InvoiceFilenameGenerator::getFileName($file, $invoice);
        $file->move($this->uploadFilePath, $fileName);

        $invoice->setFile($fileName);
        $this->repository->add($invoice);
    }
}
