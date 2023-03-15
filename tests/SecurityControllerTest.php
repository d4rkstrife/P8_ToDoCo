<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testVisitingLoginRouteWhileLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    public function testVisitingLoginRouteWhileLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $users = $userRepository->findAll(0);
        $testUser = $users[0];
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $client->request('GET', '/login');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/', $client->getRequest()->getRequestUri());
    }

    public function testVisitingLogOutWhileLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('clark95@gmail.com');
        // simulate $testUser being logged in
        $client->loginUser($user);
        $client->request('GET', '/logout');
        $client->followRedirect();
        $this->assertSelectorTextContains('.btn-success', 'Se connecter');
    }

    public function testLogIn(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

// select the button
        $buttonCrawlerNode = $crawler->selectButton('Se connecter');

        $form = $buttonCrawlerNode->form();

        $client->submit($form, [
            'email' => 'clark95@gmail.com',
            'password' => 'Password1!',
        ]);
        $client->followRedirect();
        $this->assertEquals('/', $client->getRequest()->getRequestUri());
        $this->assertSelectorTextContains('.btn-danger', 'Se déconnecter');
    }
}
