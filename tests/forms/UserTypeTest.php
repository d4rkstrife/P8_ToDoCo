<?php

namespace App\Tests\forms;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Uid\Uuid;

class UserTypeTest extends TypeTestCase
{

    public function testCreateUserForm(): void
    {
        $formData = [
            'username' => 'John',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'roles' => 'ROLE_USER',
            'uuid' => Uuid::v4(),
        ];
        $user = new User();
        $form = $this->factory->create(UserType::class, $user);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());
    }
}
