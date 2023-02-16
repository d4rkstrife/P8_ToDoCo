<?php

namespace App\Tests\forms;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTypeTest extends WebTestCase
{
    public function testCreateUserForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        // select the button
        $buttonCrawlerNode = $crawler->selectButton('.submit');
// retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();
        // set values on a form object
        $form['form[username]'] = 'username';
        $form['form[password]'] = 'Password1!';
        $form['form[email]'] = 'test@mail.com';
        $form['form[roles]'] = 'ROLE_USER';

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
