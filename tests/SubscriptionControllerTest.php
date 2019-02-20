<?php

namespace App\Test;

use App\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @method assertJson(string $content)
 * @method assertEquals(int $int, int $getStatusCode)
 */
class SubscriptionControllerTest extends WebTestCase
{

    public function testGetUsers()
    {
        $client =  static::createClient();
        $client->request('Get','/api/subscriptions',[],[],['HTTP_ACCEPT' => 'application/json']);

        $response = $client->getResponse();
        $content = $response->getContent();

        $this->assertEquals(200,$response->getStatusCode());
        $this->assertJson($content);

    }


}