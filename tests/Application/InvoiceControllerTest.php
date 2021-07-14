<?php

declare(strict_types=1);

namespace Tests\Application;

use Tests\Utilities\ApiTestCase;

class InvoiceControllerTest extends ApiTestCase
{
    public function testCreate(): void
    {
        $this->auth();

        $content = [
          'type'=>'cost',
          'name'=>'Test Invoice'
        ];

        $response = $this->request('POST', 'api/v1/invoices', $content);

        self::assertSame(201, $response->getStatusCode());
    }

}
