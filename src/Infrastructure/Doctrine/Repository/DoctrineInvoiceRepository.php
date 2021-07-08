<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Invoice;
use App\Domain\Repository\InvoiceRepository;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class DoctrineInvoiceRepository extends ServiceEntityRepository implements InvoiceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function add(Invoice $invoice): void
    {
        $this->_em->persist($invoice);
        $this->_em->flush();
    }

    public function remove(Uuid $id): void
    {
        $invoice = $this->findOneById($id);

        $this->_em->remove($invoice);
        $this->_em->flush();
    }

    /** @return Invoice[] */
    public function getByMonth(int $month, int $year): array
    {
        $date = DateTimeImmutable::createFromFormat('n/Y', "{$month}/{$year}");

        $queryBuilder = $this->createQueryBuilder('invoice');
        $between = $queryBuilder
            ->expr()
            ->between('invoice.createdAt', ':start', ':end');

        $start = $date->modify('first day of this month');
        $end = $date->modify('last day of this month');

        $query = $queryBuilder
            ->where($between)
            // TODO: add if where we'll check if file is not null
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery();

        return $query->getArrayResult();
    }

    public function getById(Uuid $id): Invoice|null
    {
        $queryBuilder = $this->createQueryBuilder('invoice');

        $query = $queryBuilder
            ->where('invoice.id = :id')
            ->setParameter('id', $id->toBinary())
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
