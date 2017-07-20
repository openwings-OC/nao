<?php

namespace Tests\AppBundle\Controller;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AbstractUsersAuthenticatedAmateurTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $clientAuthenticatedAmateur = null;

    protected $usersUrlsAuthenticated = Array(
        'profil' => '/profil/voir',
        'profil editer' => '/profil/editer',
        'changer de mot de passe' => '/changer-de-mot-de-passe',
        'mes observations' => '/mesobservations',
        'observations index' => '/observations',
        'observations ajouter' => '/observations/ajouter',
        'observations editer' => '/observations/editer/1',
        'observations supprimer' => '/observations/supprimer/2698',
        'users index' => '/users',
        'users editer' => '/users/editer/1',
        'users supprimer' => '/users/supprimer/2698',
    );

    protected $users = Array(
        'amateur' => Array(
            'username' => 'amateurtest'
        ),
        'naturaliste' => Array(
            'username' => 'naturalistetest'
        ),
        'admin' => Array(
            'username' => 'admintest'
        ),
    );

    protected function setUp()
    {
        $this->clientAuthenticatedAmateur = $this->createAuthenticatedClient($this->users['amateur']);
    }

    /**
     * @return Client
     */
    protected function createAuthenticatedClient($user)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');
        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => $user['username'],
            '_password' => 'aaaaaa',
        ));
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();
        return $client;
    }
    //****UTILISATEUR CONNECTÉ AMATEUR****//
        public function urlsProviderAuthenticatedAmateur()
        {
            return [
                [$this->usersUrlsAuthenticated['profil'],                   200],
                [$this->usersUrlsAuthenticated['profil editer'],            200],
                [$this->usersUrlsAuthenticated['changer de mot de passe'],  200],
                [$this->usersUrlsAuthenticated['mes observations'],         200],
                [$this->usersUrlsAuthenticated['observations index'],       403],
                [$this->usersUrlsAuthenticated['observations ajouter'],     200],
                [$this->usersUrlsAuthenticated['observations editer'],      403],
                [$this->usersUrlsAuthenticated['observations supprimer'],   403],
                [$this->usersUrlsAuthenticated['users index'],              403],
                [$this->usersUrlsAuthenticated['users editer'],             403],
                [$this->usersUrlsAuthenticated['users supprimer'],          403],
            ];
        }
        /**
         * @dataProvider urlsProviderAuthenticatedAmateur
         */
        public function testAuthenticatedAmateur($url, $expectedStatusCode)
        {
            $this->clientAuthenticatedAmateur->request('GET', $url);
            $this->assertEquals($expectedStatusCode, $this->clientAuthenticatedAmateur->getResponse()->getStatusCode());
        }
    //****UTILISATEUR CONNECTÉ AMATEUR****//
}
