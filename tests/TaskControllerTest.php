<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testListActionWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

//    public function testListActionWhenLoggedIn(): void
//    {
//        $client = static::createClient();
//        $userRepository = static::getContainer()->get(UserRepository::class);
//        $users = $userRepository->findAll(0);
//        $testUser = $users[0];
//        // simulate $testUser being logged in
//        $client->loginUser($testUser);
//        $client->request('GET', '/tasks');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }
}
