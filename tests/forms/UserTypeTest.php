<?php

namespace App\Tests\forms;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{

    public function testCreateUserForm(): void
    {
        $formData = [
            'username' => 'John',
            'email' => 'john@example.com',
            'password' => 'Password1!',
            'roles' => 'ROLE_USER'
        ];
        $model = new User();
        $form = $this->factory->create(UserType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());
    }
}
