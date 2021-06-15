<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Invoice;
use App\Domain\Repository\InvoiceRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineInvoiceRepository extends ServiceEntityRepository implements InvoiceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(
            $registry,
            Invoice::class
        );
    }

    public function add(Invoice $invoice): void
    {
        $this->_em->persist($invoice);
        $this->_em->flush();
    }
}
