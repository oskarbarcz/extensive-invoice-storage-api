<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Exception\DomainLogicException;
use App\Domain\Invoice;
use App\Domain\User;
use App\Domain\ValueObject\InvoiceType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class InvoiceTest extends TestCase
{
    public function testInvoiceFileShouldNotAllowOverwrite(): void
    {
        $testUser = $this->createStub(User::class);

        $invoice = self::createTestInvoice($testUser);
        $invoice->setFile('test_file.pdf');

        // try to override
        self::expectException(DomainLogicException::class);
        $invoice->setFile('another_file.pdf');
    }

    public static function createTestInvoice(User $user): Invoice
    {
        $id = Uuid::v4();
        $name = 'Test Invoice';
        $type = new InvoiceType('cost');

        return new Invoice($id, $name, $type->toString(), $user);
    }
}
