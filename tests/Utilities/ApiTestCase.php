<?php

declare(strict_types=1);

namespace Tests\Utilities;

use App\Domain\User;
use App\Infrastructure\Doctrine\Repository\DoctrineUserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class ApiTestCase extends WebTestCase
{
    protected KernelBrowser|null $client;


    protected function setUp(): void
    {
        $this->createApiClient();
        parent::setUp();
    }
    
    public function auth(): void
    {
        $repo = $this->getContainer()->get(DoctrineUserRepository::class);
        $user = $repo->findOneBy(['email'=>'test_1@example.com']);

        if($user=== null){
            $em = $this->getContainer()->get(EntityManagerInterface::class);
            $hasher = $this->getContainer()->get(UserPasswordHasherInterface::class);

            $user = User::admin(Uuid::v4(), 'test_1@example.com', 'Test User');
            $user->setHashedPassword($hasher->hashPassword($user, 'test123'));

            $em->persist($user);
            $em->flush();
        }

        $a =['username'=>$user->getEmail(),
            'password'=>'test123'];


        $respo = $this->request('POST', '/api/v1/login',$a);
        $array = json_decode($respo->getContent(), true);

        $server = ['CONTENT_TYPE' => 'application/json','HTTP_AUTHORIZATION' => "Bearer {$array['token']}"];
        $this->client = $this->getContainer()->get('test.client');
        $this->client->setServerParameters($server);
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