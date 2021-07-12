<?php

declare(strict_types=1);

namespace App\Application\Command;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateInvoiceCommand
{
    #[Assert\Uuid]
    private Uuid|null $id;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\Choice(choices: ['cost', 'revenue'])]
    private string $type;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;

        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
