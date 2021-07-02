<?php

declare(strict_types=1);

namespace App\Application\Query;

use http\Env\Response;
use Symfony\Component\Uid\Uuid;

final class GetInvoiceFileQuery
{
    public function __invoke(Uuid $id): File
    {
    }
}
