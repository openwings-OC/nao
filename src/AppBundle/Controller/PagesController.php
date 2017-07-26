<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Entity\User;
use AppBundle\Form\ObservationType;
use AppBundle\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;

class PagesController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Observation');
        $lastObservations = $em->findLastObservations(3);

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

    /**
     * @route("/adherer", name="app_adherer")
     */
    public function adhererAction(){
        return $this->render(':pages:adherer.html.twig');
    }

    /**
     * @route("/association", name="app_association")
     */
    public function associationAction(){
        return $this->render(':pages:association.html.twig');
    }

    /**
     * @route("/equipe", name="app_equipe")
     */
    public function equipeAction(){
        return $this->render(':pages:equipe.html.twig');
    }

    /**
     * @route("/mission", name="app_mission")
     */
    public function missionAction(){
        return $this->render(':pages:mission.html.twig');
    }

    /**
     * @route("/projet", name="app_projet")
     */
    public function projetAction(){
        return $this->render(':pages:projet.html.twig');
    }

    /**
     * @route("/participation-a", name="app_landing-a")
     */
    public function landingAAction(){
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        return $this->render(':pages:landing-a.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @route("/participation-b", name="app_landing-b")
     */
    public function landingBAction(){
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        return $this->render(':pages:landing-b.html.twig', array(
            'form' => $form->createView()
        ));
    }


}
