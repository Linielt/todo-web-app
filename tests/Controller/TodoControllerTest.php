<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
//    public function testSomething(): void
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/');
//
//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h1', 'Hello World');
//    }

    public function testVisitingTodosWhileLoggedIn()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('johndoe@email.com');

        $client->loginUser($testUser);

        $client->request('GET', '/todos');
        $this->assertResponseIsSuccessful();
    }

    public function testVisitingTodosWhileNotLoggedIn()
    {
        $client = static::createClient();

        $client->followRedirects(false);
        $client->request('GET', '/todos');
        $this->assertResponseIsSuccessful();
    }

    public function testCreatingTodoWhileLoggedIn()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('johndoe@email.com');

        $client->loginUser($testUser);

        $client->request('GET', '/todo');

        $crawler = $client->submitForm('Save', [
            'todo_item_form[title]' => 'test',
            'todo_item_form[description]' => 'test description',
            'todo_item_form[dueDate]' => date('Y-m-d H:m:s'),
        ]);

        $this->assertResponseRedirects();
    }

    public function testCreatingTodoWhileNotLoggedIn()
    {
        $client = static::createClient();

        $client->request('GET', '/todo');

        $this->assertResponseRedirects();
    }


}
