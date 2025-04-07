<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testGetHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/deal/list');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetDealPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/deal/show/52');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostDealPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/deal/create');

        $form = $crawler->filter('form[name=deal_form]')->form([
            'deal_form[name]' => 'test',
            'deal_form[price]' => '10',
            'deal_form[description]' => 'test description',
            'deal_form[categories]' => 5,
            'deal_form[enable]' => 1,
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('/deal/list');

    }
}
