<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Observation');
        $lastObservations = $em->find4LastObservations();
        return $this->render('pages/homepage.html.twig', array(
            'list' => $lastObservations
        ));
    }

    /**
     * @route("/guide-debutant", name="app_guide_debutant")
     */
    public function debutantAction(){
        return $this->render(':pages:guide_debutant.html.twig');
    }
}
