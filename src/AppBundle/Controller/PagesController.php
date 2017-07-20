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
     * @route("/search", name="app_search")
     */
    public function searchAction(Request $request)
    {

        $em = $this->getDoctrine()->getRepository('AppBundle:Taxref');
        if ($request->isMethod('POST')) {
            $bird = $em->findBirdByLetter($_POST['search']/*, $_GET['page']*/);
        }
        elseif(!empty($_GET['page'])){
            $bird = $em->findAll();
        }
        else if(empty($_GET['page'])){
            $bird = $em->findAll();
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $bird,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 50)
        );
        $pagination->setTemplate('modules:pagination.html.twig');
        return $this->render('pages/search.html.twig', array(
            'pagination' => $pagination,
            'bird' => $bird
        ));
    }

    /**
     *
     * @route("/results/{page}", name="app_results")
     */
    public function resultAction($page){
        $em = $this->getDoctrine()->getRepository('AppBundle:Taxref');
        $list = $em->findBirdByLetterLimited($page , 50);

        //Service pagination
        $pagination = array(
            'page' => $page,
            'nomRoute' => 'app_results',
            'paramsRoute' => array()
        );

        return $this->redirect('pages/search.html.twig', array(
            'list' => $list,
            'pagination' => $pagination
        ));
    }

    /**
     * @route("/autocomplete", name="app_autocomplete")
     */
    public function autocompleteAction(Request $request){
        if($request->isXmlHttpRequest()){
            $letter = $request->get('bird');
            $requete = $this->getDoctrine()->getRepository('AppBundle:Taxref')->findBirdByLetter($letter);
            $numberRequest = $this->getDoctrine()->getRepository('AppBundle:Observation');
            //Service qui créé le tableau JSON
            $list = [];
            foreach($requete as $bird){
               $number = $numberRequest->getNumberOfObservationsByBird($bird);
               $list[] = ['value' => $bird->getNomVern(), 'number' => $number, 'id' => $bird->getCdNom()];
                if($bird->getNomVern() === ''){
                    array_push($list, $bird->getlbNom());
                }
            }

            $response = new Response();
            $response->setContent(json_encode(
                array('list' => $list)
            ),
                array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json')
            );

            return $response;
        }
    }

    /**
     * @route("/specy/{id}", name="app_specy")
     */
    public function specyAction(Request $request){
        $specy = $this->getDoctrine()->getRepository('AppBundle:Taxref')->findSpecyByBirdId((int)$request->get('id'));
        $numberRequest = $this->getDoctrine()->getRepository('AppBundle:Observation')->getNumberOfObservationsByBird($specy->getCdNom());
        $observations = $this->getDoctrine()->getRepository('AppBundle:Observation')->findObservationsBySpecieId((int)$request->get('id'));
        return $this->render('pages/specy.html.twig', array(
            'specy' => $specy,
            'observations' => $observations,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/observation/{id}", name="app_observation")
     */
    public function observationAction(Request $request){
        $observation = $this->getDoctrine()->getRepository('AppBundle:Observation')->findObservationById((int)$request->get('id'));
        $specy = $observation->getSpecy()->getCdNom();
        $observations = $this->getDoctrine()->getRepository('AppBundle:Observation')->findObservationsBySpecieId($specy);
        return $this->render('pages/observation.html.twig', array(
            'specy' => $specy,
            'observation' => $observation,
            'observations' => $observations
        ));
    }

    /**
     *
     * @route("/add/", name="app_addObservation")
     */
    public function addObservationAction(Request $request){
        $observation = new Observation();
        $form = $this->createForm(ObservationType::class, $observation);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid() ){
            $em = $this->getDoctrine()->getManager();
            //Service hydrater objet
            $observation->getImage()->upload($observation->getCreatedAt(), $observation->getSpecy()->getCdNom());
            $date = $observation->getCreatedAt();
            $observation->setState('pending');
            $observation->setUpdatedAt($date);
            $observation->setAuthor('Robin');

            $em->persist($observation);
            $em->flush();

        }
        return $this->render(':crud:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @route("/observation_map", name="app_observation_map")
     */
    public function observationMap(Request $request){
        if($request->isXmlHttpRequest()){
            $specyId = (int)$request->get('bird');
            $em = $this->getDoctrine()->getRepository('AppBundle:Observation');
            $req = $em->findObservationsBySpecieId($specyId);

            $list = [];
            foreach($req as $bird){
                $list[] = [$bird->getLatitude(), $bird->getLongitude(), $bird->getId()];
            }
            return new JsonResponse(array('list' => $list));
            /*$response = new Response();
            $response->setContent(json_encode(
                array('list' => $list)
            ),
                array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json')
            );

            return $response;*/
        }

        $form = $this->createForm(ObservationType::class);
        return $this->render(':pages:observation_map.html.twig', array(
            'form' => $form->createView(),
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
     * @route("/landing-a", name="app_landing-a")
     */
    public function landingAAction(){
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        return $this->render(':pages:landing-a.html.twig');
    }

    /**
     * @route("/landing-b", name="app_landing-b")
     */
    public function landingBAction(){
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        return $this->render(':pages:landing-b.html.twig');
    }
}

