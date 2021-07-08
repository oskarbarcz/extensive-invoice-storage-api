<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\DomainLogicException;
use App\Domain\Trait\SerializerTrait;
use App\Infrastructure\Doctrine\Repository\DoctrineInvoiceRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
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

    #[ORM\Column('file_id', type: 'string', nullable: true)]
    private string|null $file = null;

    #[ORM\Column('type', type: 'string')]
    private string $type;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $owner;

    #[ORM\Column('created_at', type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(Uuid $id, string $name, string $type, User $owner)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->owner = $owner;

        $this->createdAt = new DateTimeImmutable('now', new DateTimeZone('Europe/Warsaw'));
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

    public function getFile(): string|null
    {
        return $this->file;
    }

    public function setFile(?string $file): void
    {
        if (null !== $this->file) {
            throw DomainLogicException::translatable('exception.invoice.file_already_set');
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

    public function getOwner(): User
    {
        return $this->owner;
    }
}
