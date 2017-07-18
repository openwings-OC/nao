<?php

namespace Tests\AppBundle\Controller;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractFOSUserBundleControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client = null;
    protected $usersUrlsUnauthenticated = Array(
        '/connexion',
        '/deconnexion',
        '/changer-de-mot-de-passe',
    );
    protected $usersUrlsAuthenticated = Array(
        '/profil',
        '/profil/editer',
        '/mesobservations',
        '/observations',
        '/observations/ajouter',
        '/observations/editer/',
        '/observations/supprimer/',
        '/users',
        '/users/editer/',
        '/users/supprimer/',
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
            public function testsUnauthenticated()
            {
                foreach ($this->usersUrlsAuthenticated as $url){
                    $this->client = $this->createUnauthenticatedClient();
                    $this->client->setMaxRedirects(5);
                    $crawler = $this->client->request('GET', $url);
                    $statusCode =  $this->client->getResponse()->getStatusCode();
                    $this->assertEquals(301 || 302, $statusCode);
                    if($statusCode === 404) dump($url);
                    /*$crawler = $this->client->followRedirect();
                    $this->assertRegExp('/\/connexion$/', $this->client->getResponse()->headers->get('location'));
*/
                }
            }
    //****UTILISATEUR NON CONNECTÉ****//
    //****UTILISATEUR CONNECTÉ AMATEUR****//
    //****UTILISATEUR CONNECTÉ AMATEUR****//
    //****UTILISATEUR CONNECTÉ NATURALISTE****//
    //****UTILISATEUR CONNECTÉ NATURALISTE****//
    //****UTILISATEUR CONNECTÉ ADMINISTRATEUR****//
    //****UTILISATEUR CONNECTÉ ADMINISTRATEUR****//
}
