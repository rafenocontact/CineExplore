<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
	public function testFindMovie(): void
	{
		$client = static::createClient();
		$client->request('GET', '/findMovie/avatar');

		$this->assertResponseIsSuccessful();
	}
}