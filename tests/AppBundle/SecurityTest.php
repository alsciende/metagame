<?php

namespace tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of SecurityTest
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class SecurityTest extends WebTestCase
{
    protected function getAuthenticatedClient (string $username = 'user', string $password = 'user'): Client
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => $username,
            'PHP_AUTH_PW' => $password,
        ));
    }

    protected function getAnonymousClient (): Client
    {
        return static::createClient();
    }

    public function testHomepage ()
    {
        $client = $this->getAnonymousClient();
        $client->request('GET', "/");
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testSecurity()
    {
        $client = $this->getAnonymousClient();
        $client->request('GET', "/profile");
        $this->assertEquals(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals(
            '/login',
            $client->getResponse()->headers->get('location')
        );
    }

    public function testBasicLogin()
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', '/profile');
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testBasicOauth()
    {
        $client = $this->getAuthenticatedClient();
        $crawler = $client->request(
            'GET',
            "/oauth/v2/auth",
            [
                'client_id' => '1_test',
                'response_type' => 'code',
                'redirect_uri' => 'http://httpbin.org/get',
            ]
        );
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $form = $crawler->selectButton('accepted')->form();
        $client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals(
            0,
            strpos($client->getResponse()->headers->get('location'), 'http://httpbin.org/get')
        );
    }
}