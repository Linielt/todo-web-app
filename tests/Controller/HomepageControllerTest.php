<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    public function testIndexWhileLoggedIn(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('johndoe@email.com');
        $client->loginUser($testUser);

        $client->request('GET', '/');

        $this->assertResponseRedirects();
    }

    public function testIndexWhileNotLoggedIn(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
