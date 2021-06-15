<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateInvoiceCommand;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateInvoiceHandler implements MessageHandlerInterface
{
    #[NoReturn]
    public function __invoke(
        CreateInvoiceCommand $command
    ): void {
        dd($command);
    }
}
