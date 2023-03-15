<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

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
    public function testCreateUserWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in
        $client->loginUser($user);
        $client->request('GET', '/users/create');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/', $client->getRequest()->getRequestUri());
    }
    public function testCreateUserWhenLoggedOut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('/users/create', $client->getRequest()->getRequestUri());
    }
    public function testCreateAdminUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/create');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'user[username]' => 'username',
            'user[password][first]' => 'Password1!',
            'user[password][second]' => 'Password1!',
            'user[email]' => 'email@mail.com',
            'user[roles]' => 'ROLE_ADMIN'
        ]);
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());
        $this->assertSelectorTextContains('.alert-success strong', 'Superbe !');
    }
    public function testRoleChangeWhenLoggedOut(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $subject = $userRepository->findOneBy(['email'=>'dkub@schuppe.com']);
        $uuid = $subject->getUuid();
        $client->request('GET', '/users/'.$uuid.'/roleChange');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/login', $client->getRequest()->getRequestUri());
    }
    public function testChangeRoleFromUserToAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email'=>'clark95@gmail.com']);
        // simulate $testUser being logged in
        $client->loginUser($user);
        $subject = $userRepository->findOneBy(['email'=>'dkub@schuppe.com']);
        $uuid = $subject->getUuid();
        $client->request('GET', '/users/'.$uuid.'/roleChange');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('/users', $client->getRequest()->getRequestUri());
    }

}
