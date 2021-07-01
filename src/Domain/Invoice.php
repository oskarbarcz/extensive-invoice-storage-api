<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Trait\SerializerTrait;
use App\Infrastructure\Doctrine\Repository\DoctrineInvoiceRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DoctrineInvoiceRepository::class)]
#[ORM\Table(name: 'invoices')]
class Invoice
{
    use SerializerTrait;

    #[ORM\Id]
    #[ORM\Column('id', type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column('name', type: 'string')]
    private string $name;

    #[ORM\Column('file_id', type: 'uuid', nullable: true)]
    private ?Uuid $file = null;

    #[ORM\Column('type', type: 'string')]
    private string $type;

    #[ORM\Column('created_at', type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(Uuid $id, string $name, string $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;

        $this->createdAt = new DateTimeImmutable();
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

    public function getFile(): ?Uuid
    {
        return $this->file;
    }

    public function setFile(?Uuid $file): void
    {
        if (null !== $this->file) {
            throw new RuntimeException('Invoice file cannot be overwritten.');
        }

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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
