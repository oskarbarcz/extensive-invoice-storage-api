<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Fixture;

use App\Domain\Invoice;
use App\Domain\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

final class CommonFixture extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $genericUsers = [];

        foreach (range(0, 10) as $j) {
            $genericUser = User::generic(Uuid::v4(), "generic{$j}@example.com", "Generic User {$j}");

            $password = $this->hasher->hashPassword($genericUser, "generic{$j}");
            $genericUser->setHashedPassword($password);
            $genericUsers[] = $genericUser;

            $manager->persist($genericUser);
        }

        foreach (range(0, 10) as $k) {
            $adminUser = User::admin(Uuid::v4(), "admin{$k}@example.com", "Admin User {$k}");

            $password = $this->hasher->hashPassword($adminUser, "admin{$k}");
            $adminUser->setHashedPassword($password);

            $manager->persist($adminUser);
        }

        foreach (range(0, 10) as $i) {
            foreach ($genericUsers as $l => $invoiceOwner) {
                $invoice = new Invoice(Uuid::v4(), "Invoice {$i}{$l}", 'cost', $invoiceOwner);

                $manager->persist($invoice);
            }
        }

        $manager->flush();
    }
}
