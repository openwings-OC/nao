<?php
/**
 * Created by PhpStorm.
 * User: JulienHalgand
 * Date: 19/07/2017
 * Time: 12:08
 */

namespace Tests\AppBundle\Controller;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;



class AbstractAuthenticatedAmateurUserTest extends WebTestCase
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
}