<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLogInWithCorrectCredentials(): void
    {
        $server = ['CONTENT_TYPE' => 'application/json'];

        $client = static::createClient([], $server);

        $testUserData = [
            'username' => 'generic1@example.com',
            'password' => 'generic1',
        ];

        $content = json_encode($testUserData);


        $client->request('POST', 'api/v1/login', content: $content);

        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertJson($client->getResponse()->getContent());

        $apiReceivedData = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('token', $apiReceivedData);
        self::assertIsString($apiReceivedData['token']);
    }

    public function testLogInWithIncorrectCredentials(): void
    {
        $server = ['CONTENT_TYPE' => 'application/json'];

        $client = static::createClient([], $server);

        $testUserData = [
            'username' => 'generic1@example.com',
            'password' => 'some-incorrect-password',
        ];

        $content = json_encode($testUserData);

        $client->request('POST', 'api/v1/login', content: $content);

        self::assertSame(401, $client->getResponse()->getStatusCode());
        self::assertJson($client->getResponse()->getContent());

        $apiReceivedData = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('message', $apiReceivedData);
        self::assertSame('Invalid credentials.', $apiReceivedData['message']);
    }
}
