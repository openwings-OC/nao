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
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @route("/participation", name="app_participation")
     */
    public function participationAction(){
        $route = $this->get('app.random_landing')->chooserNumberRandom();
        return $this->redirectToRoute($route);
    }

    /**
     * @route("/mentions", name="app_mentions")
     */
    public function mentionsAction(){
        return $this->render(':pages:mentions.html.twig');
    }

    /**
     * @route("/plan-du-site", name="app_plan_site")
     */
    public function planAction(){
        return $this->render(':pages:plan-site.html.twig');
    }

    /**
     * @route("/participation-a", name="app_landing-a")
     */
    public function landingAAction(Request $request){
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }
            return $this->render(':pages:landing-a.html.twig', array(
                'form' => $form->createView(),
            ));
    }
    /**
     * @route("/participation-b", name="app_landing-b")
     */
    public function landingBAction(Request $request){
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }
            return $this->render(':pages:landing-b.html.twig', array(
                'form' => $form->createView(),
            ));
        }
}
