<?php

namespace Tests\AppBundle\Controller;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AbstractUsersUnauthenticatedTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $clientUnauthenticated = null;

    protected $usersUrlsAuthenticated = Array(
        'profil'                    => '/profil/voir',
        'profil editer'             => '/profil/editer',
        'changer de mot de passe'   => '/changer-de-mot-de-passe',
        'mes observations'          => '/mesobservations',
        'observations index'        => '/observations',
        'observations ajouter'      => '/observations/ajouter',
        'observations editer'       => '/observations/editer/1',
        'observations supprimer'    => '/observations/supprimer/2698',
        'users index'               => '/users',
        'users editer'              => '/users/editer/1',
        'users supprimer'           => '/users/supprimer/2698',
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

    //****UTILISATEUR NON CONNECTÉ****//
        public function urlsProviderUnauthenticated()
        {
            return [
                ['/\/connexion$/', $this->usersUrlsAuthenticated['profil'],                 302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['profil editer'],          302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['mes observations'],       302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['observations index'],     302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['observations ajouter'],   302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['observations editer'],    302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['observations supprimer'], 302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['users index'],            302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['users editer'],           302],
                ['/\/connexion$/', $this->usersUrlsAuthenticated['users supprimer'],        302],
            ];
        }
        /**
         * @dataProvider urlsProviderUnauthenticated
         */
        public function testUnauthenticatedUrls($urlRedirection, $url, $expectedStatusCode)
        {
            $crawler = $this->clientUnauthenticated->request('GET', $url);
            $statusCode = $this->clientUnauthenticated->getResponse()->getStatusCode();
            $this->assertEquals($expectedStatusCode, $statusCode);
            if($statusCode === 301) $crawler = $this->clientUnauthenticated->followRedirect();

            $this->assertRegExp($urlRedirection, $this->clientUnauthenticated->getResponse()->headers->get('location'));
        }
    //****UTILISATEUR NON CONNECTÉ****//

}
