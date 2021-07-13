<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\Exception\DomainLogicException;
use App\Domain\Invoice;
use App\Domain\User;
use App\Domain\ValueObject\InvoiceType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class InvoiceTest extends TestCase
{
    private Invoice $sut;

    protected function setUp(): void
    {
        $id = Uuid::v4();
        $name = 'Test Invoice';
        $type = new InvoiceType('cost');
        $user = $this->createStub(User::class);

        $this->sut = new Invoice($id, $name, $type->toString(), $user);
    }

    /**
     * This method should not allow file to be overwritten
     */
    public function testSetFile(): void
    {
        $this->sut->setFile('test_file.pdf');

        // try to override
        self::expectException(DomainLogicException::class);
        $this->sut->setFile('another_file.pdf');
    }
}
