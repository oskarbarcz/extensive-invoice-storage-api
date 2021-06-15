<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Fixture;

use App\Domain\Invoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class CommonFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (range(0, 10) as $i) {
            $invoice = new Invoice(Uuid::v4(), "Invoice {$i}", Uuid::v4(), 'cost');
            
            $manager->persist($invoice);
        }

        $manager->flush();
    }
}
