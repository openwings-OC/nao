<?php

namespace Tests\AppBundle\Controller;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;

class AbstractUnauthenticatedUserTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client = null;

    protected $usersUrlsAuthenticated = Array(
        'profil'                    => '/profil',
        'profil editer'             => '/profil/editer',
        'mes observations'          => '/mesobservations',
        'observations index'        => '/observations',
        'observations ajouter'      => '/observations/ajouter',
        'observations editer'       => '/observations/editer/',
        'observations supprimer'    => '/observations/supprimer/',
        'users index'               => '/users',
        'users editer'              => '/users/editer/',
        'users supprimer'           => '/users/supprimer/',
    );

    /**
     * @return Client
     */
    public function createUnauthenticatedClient()
    {
        $client = static::createClient();

        return $client;
    }
    /**
     * @return Client
     */
    protected function createAuthenticatedClient($username)
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => 'REPLACE_WITH_YOUR_TEST_USERNAME'));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set('_security_' . $firewallName,
            serialize($container->get('security.context')->getToken()));
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }
    //****CREATION D'UN NOUVEL UTILISATEUR****//

    //****CREATION D'UN NOUVEL UTILISATEUR****//
    //****CONNEXION DECONNEXION****//

    //****CONNEXION DECONNEXION****//
    //****UTILISATEUR NON CONNECTÉ****//
        //Pages ou l'utilisateur non conncecté est redirigé vers la page connexion
            public function testProfil()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['profil']);
                $this->assertEquals(301, $this->client->getResponse()->getStatusCode());
                $crawler = $this->client->followRedirect();
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testProfilEditer()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['profil editer']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testMesObservations()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['mes observations']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testObservationsIndex()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['observations index']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testObservationsAjouter()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['observations ajouter']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testObservationsEditer()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['observations editer']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testObservationsSupprimer()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['observations supprimer']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testUsersIndex()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['users index']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testUsersEditer()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['users editer']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
            public function testUsersSupprimer()
            {
                $this->client = $this->createUnauthenticatedClient();
                $crawler = $this->client->request('GET', $this->usersUrlsAuthenticated['users supprimer']);
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
            }
    //****UTILISATEUR NON CONNECTÉ****//
    //****UTILISATEUR CONNECTÉ AMATEUR****//

    //****UTILISATEUR CONNECTÉ AMATEUR****//
    //****UTILISATEUR CONNECTÉ NATURALISTE****//

    //****UTILISATEUR CONNECTÉ NATURALISTE****//
    //****UTILISATEUR CONNECTÉ ADMINISTRATEUR****//

    //****UTILISATEUR CONNECTÉ ADMINISTRATEUR****//
}
