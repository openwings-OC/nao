<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PagesControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client = null;
    /**
     * @return Client
     */
    protected function createUnauthorizedClient()
    {
        $client = static::createClient();

        return $client;
    }
    public function testIndexAction()
    {
        $this->client = $this->createUnauthorizedClient();

        $crawler = $this->client->request('GET', '');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    public function testConnexion()
    {
        $this->client = $this->createUnauthorizedClient();

        $crawler = $this->client->request('GET', '/connexion');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    public function testInscription()
    {
        $this->client = $this->createUnauthorizedClient();

        $crawler = $this->client->request('GET', '/inscription/creer-un-compte');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
