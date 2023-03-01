<?php

namespace App\Tests;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testListActionWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());
    }

    public function testListActionWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in

        $client->loginUser($user);
        $client->request('GET', '/tasks');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('/tasks', $client->getRequest()->getRequestUri());
    }
    public function testDoneListActionWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/doneTasks');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());
    }
    public function testDoneListActionWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in

        $client->loginUser($user);
        $client->request('GET', '/doneTasks');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('/doneTasks', $client->getRequest()->getRequestUri());
    }
    public function testCreateTaskRouteWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());

    }
    public function testCreateTaskRouteWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in

        $client->loginUser($user);
        $client->request('GET', '/tasks/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('/tasks/create', $client->getRequest()->getRequestUri());
    }
    public function testTaskFormIsSubmit(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in

        $client->loginUser($user);
        $crawler = $client->request('GET', '/tasks/create');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'task[title]' => 'title',
            'task[content]' => 'content',
        ]);
        $client->followRedirect();
        $this->assertEquals('/tasks', $client->getRequest()->getRequestUri());
        $this->assertSelectorTextContains('.alert-success strong', 'Superbe !');
    }
    public function testEditTaskWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/11/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());
    }
    public function testEditTaskWhenLoggedIn():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in

        $client->loginUser($user);

        $client->request('GET', '/tasks/11/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('/tasks/11/edit', $client->getRequest()->getRequestUri());
    }
    public function testEditTaskForm(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in

        $client->loginUser($user);

        $crawler = $client->request('GET', '/tasks/11/edit');
        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'task[title]' => 'title',
            'task[content]' => 'content',
        ]);
        $client->followRedirect();
        $this->assertEquals('/tasks', $client->getRequest()->getRequestUri());
        $this->assertSelectorTextContains('.alert-success strong', 'Superbe !');
    }
    public function testToggleActionWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/11/toggle');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());
    }
    public function testToggleActionWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in
        $client->loginUser($user);
        $client->request('GET', '/tasks/11/toggle');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/tasks', $client->getRequest()->getRequestUri());
        $this->assertSelectorTextContains('.alert-success strong', 'Superbe !');
    }
    public function testDeleteTaskActionWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/11/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());
    }
    public function testDeleteTaskActionWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in
        $client->loginUser($user);
        $client->request('GET', '/tasks/11/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/tasks', $client->getRequest()->getRequestUri());
       // $this->assertSelectorTextContains('.alert-success', 'La tâche a bien été supprimée.');
    }

}
