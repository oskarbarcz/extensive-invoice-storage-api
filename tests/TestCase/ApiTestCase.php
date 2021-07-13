<?php

declare(strict_types=1);

namespace Tests\TestCase;

use App\Domain\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    protected KernelBrowser|null $client;


    protected function setUp(): void
    {
        $this->createApiClient();
        parent::setUp();
    }
    
    public function auth(User $user): void
    {
    }

    public function request(string $method, string $uri, array $content)
    {
        if ($this->client === null) {
            $this->createApiClient();
        }

        $json = json_encode($content);

        $this->client->request($method, $uri, content: $json);

        return $this->client->getResponse();

    }

    public function createApiClient(): void
    {
        $server = ['CONTENT_TYPE' => 'application/json'];
        $this->client = static::createClient([], $server);
    }
}