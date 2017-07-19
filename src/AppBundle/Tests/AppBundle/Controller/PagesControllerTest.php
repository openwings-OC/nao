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
    protected $usersUrlsUnauthenticated = Array(
        //Pages statiques
        'adherer a nao' => '/adherer-a-nao',
        'notre equipe' => '/notre-equipe',
        'guide du debutant' => '/guide-du-debutant',
        'association et notre mission' => '/association-notre-mission',
        'le projet et ses partenaires' => '/projet-et-ses-partenaires',
        'plan du site' => '/plan-du-site',
        'landing page A' => '/participation-a',
        'landing page B' => '/participation-b',
        //Pages dynamiques
        'page accueil' => '/',
        'dernieres observations' => '/dernieres-observations',
        'page rechercher une espece' => '/rechercher-une-espece',
        'resultats de recherche' => '/resultats',
        'carte des observations' => '/carte-des-observations',
        'voir une observation' => '/observations/voir/',
        'contact' => '/nous-contacter',
        //Users
        'connexion' => '/connexion',
        'inscription' => '/inscription/creer-un-compte',
        'changer de mot de passe' => '/changer-de-mot-de-passe',
    );
    /**
     * @return Client
     */
    protected function createUnauthorizedClient()
    {
        $client = static::createClient();

        return $client;
    }
    public function testHomepage()
    {
        $this->client = $this->createUnauthorizedClient();

        $crawler = $this->client->request('GET', $this->usersUrlsUnauthenticated['page accueil']);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    public function testConnexion()
    {
        $this->client = $this->createUnauthorizedClient();

        $crawler = $this->client->request('GET', $this->usersUrlsUnauthenticated['connexion']);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    public function testInscription()
    {
        $this->client = $this->createUnauthorizedClient();

        $crawler = $this->client->request('GET', $this->usersUrlsUnauthenticated['inscription']);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
