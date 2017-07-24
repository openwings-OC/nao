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
    protected $inscriptionHydrator = Array(
        'fos_user_registration_form[lastname]' => 'a@a.aa',
        'fos_user_registration_form[firstname]' => 'a@a.aa',
        'fos_user_registration_form[email]' => 'a@a.aa',
        'fos_user_registration_form[email_confirmation]' => 'a@a.aa',
        'fos_user_registration_form[username]' => 'a@a.aa',
        'fos_user_registration_form[plainPassword][first]' => 'aaaaaa',
        'fos_user_registration_form[plainPassword][second]' => 'aaaaaa',
    );

    protected function setUp(){
        $this->clientUnauthenticated = $this->createUnauthenticatedClient();
    }

    protected function createUnauthenticatedClient()
    {
        $client = static::createClient();

        return $client;
    }

    private function hydrator($form){
        foreach ($this->inscriptionHydrator as $key => $value){
            $form[$key] = $value;
        }
    }
    //****INSCRIPTION****//
        public function testInscriptionForm(){
            $crawler = $this->clientUnauthenticated->request('GET', '/inscription/creer-un-compte');
            $form = $crawler->selectButton('Créer un compte')->form();
            $this->hydrator($form);
            $this->clientUnauthenticated->submit($form);
            $this->assertTrue(
                $this->clientUnauthenticated->getResponse()->isRedirect('/inscription/confirmer')
            );
        }
    //****INSCRIPTION****//
}
