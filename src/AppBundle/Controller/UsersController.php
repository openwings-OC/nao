<?php
/**
 * Created by PhpStorm.
 * User: JulienHalgand
 * Date: 19/07/2017
 * Time: 20:05
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
    /**
     * @Route("/users", name="app_usersindex")
     */
    public function indexAction(Request $request)
    {
        return $this->render('pages/users/index.html.twig');
    }
    /**
     * @Route("/users/editer/{id}", name="app_usersedit")
     */
    public function editAction(Request $request)
    {
        return $this->render('pages/users/edit.html.twig');
    }
    /**
     * @Route("/users/supprimer/{id}", name="app_usersdelete")
     */
    public function deleteAction(Request $request)
    {
        return $this->redirectToRoute('app_usersindex');
    }
}