<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUsersListWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());
    }
    public function testUsersListWhenNotAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'dkub@schuppe.com']);
        // simulate $testUser being logged in
        $client->loginUser($user);
        $client->request('GET', '/users');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    public function testUsersListWhenAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in
        $client->loginUser($user);
        $client->request('GET', '/users');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('/users', $client->getRequest()->getRequestUri());
    }
}
