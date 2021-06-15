<?php

declare(strict_types=1);

namespace App\Domain;

use App\Infrastructure\Doctrine\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\Table(name: 'invoices')]
final class Invoice
{
    #[ORM\Id]
    #[ORM\Column('id', type: 'uuid')]
    private Uuid $id;

    #[ORM\Column('name', type: 'string')]
    private string $name;

    #[ORM\Column('id', type: 'uuid')]
    private Uuid $file;

    #[ORM\Column('name', type: 'string')]
    private string $type;

    public function __construct(Uuid $id, string $name, Uuid $file, string $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->file = $file;
        $this->type = $type;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFile(): Uuid
    {
        return $this->file;
    }

    public function setFile(Uuid $file): void
    {
        $this->file = $file;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
