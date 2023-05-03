<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    public function setUp(): void
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    private function newUser(): User
    {
        return (new User)
            ->setEmail('test@mail.com')
            ->setPassword('Password1!')
            ->setUsername('Test');
    }

    public function testValidEntity(): void
    {
        $user = $this->newUser();
        $errors = $this->validator->validate($user);
        $this->assertCount(0, $errors);
    }

    public function testNotValidPasswordBlank(): void
    {
        $user = $this->newUser();
        $user->setPassword('');
        $errors = $this->validator->validate($user);
        $this->assertCount(1, $errors);

    }

    public function testNotValidPasswordRegex(): void
    {
        $user = $this->newUser();
        $user->setPassword('invalid');
        $errors = $this->validator->validate($user);
        $this->assertCount(1, $errors);
    }

    public function testNotValidEmailRegex(): void
    {
        $user = $this->newUser();
        $user->setEmail('invalid');
        $errors = $this->validator->validate($user);
        $this->assertCount(1, $errors);
    }

    public function testNotValidEmailBlank(): void
    {
        $user = $this->newUser();
        $user->setEmail('');
        $errors = $this->validator->validate($user);
        $this->assertCount(1, $errors);
    }

}
