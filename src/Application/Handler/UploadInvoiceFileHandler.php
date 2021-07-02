<?php

namespace App\Application\Handler;

use App\Application\Command\UploadInvoiceFileCommand;
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
        $invoice = $this->repository->getById($uuid);

        if($invoice === null){
            dd('INVOICE NOT FOUND');
        }

        if($invoice->getFile()!== null){
            dd('FILE FOR THIS INVOICE IS ALREADY SET.');
        }

        $file = new UploadedBase64File($command->getFile(), '');
        $fileName = InvoiceFilenameGenerator::getFileName($file,$invoice);
        $file->move($this->uploadFilePath, $fileName);

        $invoice->setFile($fileName);
        $this->repository->add($invoice);
    }
}
