<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testVisitingWhileLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertSelectorTextContains('label', 'Email');
    }


    public function testVisitingWhileLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $users = $userRepository->findAll(0);
        $testUser = $users[0];
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
