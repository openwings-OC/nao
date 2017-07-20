<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PagesControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $clientUnauthenticated = null;
    protected $usersUrlsStatic = Array(
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
        'voir une observation' => '/observations/voir/1',
        'contact' => '/nous-contacter',
        //Users
        'connexion' => '/connexion',
        'inscription' => '/inscription/creer-un-compte',

    );

    protected function setUp(){
        $this->clientUnauthenticated = $this->createUnauthenticatedClient();
    }
    /**
     * @return Client
     */
    public function createUnauthenticatedClient()
    {
        $client = static::createClient();

        return $client;
    }

    //****PAGES STATIQUES****//
        public function urlsProviderUnauthenticated()
        {
            return [
                [$this->usersUrlsStatic['adherer a nao'],                    200],
                [$this->usersUrlsStatic['notre equipe'],                     200],
                [$this->usersUrlsStatic['guide du debutant'],                200],
                [$this->usersUrlsStatic['association et notre mission'],     200],
                [$this->usersUrlsStatic['le projet et ses partenaires'],     200],
                [$this->usersUrlsStatic['plan du site'],                     200],
                [$this->usersUrlsStatic['landing page A'],                   200],
                [$this->usersUrlsStatic['landing page B'],                   200],
                [$this->usersUrlsStatic['page accueil'],                     200],
                [$this->usersUrlsStatic['page rechercher une espece'],       200],
                [$this->usersUrlsStatic['resultats de recherche'],           200],
                [$this->usersUrlsStatic['carte des observations'],           200],
                [$this->usersUrlsStatic['voir une observation'],             200],
                [$this->usersUrlsStatic['contact'],                          200],
                [$this->usersUrlsStatic['connexion'],                        200],
                [$this->usersUrlsStatic['inscription'],                      200],
            ];
        }
        /**
         * @dataProvider urlsProviderUnauthenticated
         */
        public function testUnauthenticatedUrls($url, $expectedStatusCode)
        {
            $crawler = $this->clientUnauthenticated->request('GET', $url);
            $statusCode = $this->clientUnauthenticated->getResponse()->getStatusCode();
            $this->assertEquals($expectedStatusCode, $statusCode);
        }
    //****PAGES STATIQUES****//

}
