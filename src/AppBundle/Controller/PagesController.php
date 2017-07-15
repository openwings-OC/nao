<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
<<<<<<< HEAD:src/AppBundle/Controller/PagesController.php
        return $this->render('pages/homepage.html.twig');
=======
        // replace this example code with whatever you need

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
>>>>>>> fb7bc011e20777f138b1a355a0d268bffa26abb4:src/AppBundle/Controller/DefaultController.php
    }


}
