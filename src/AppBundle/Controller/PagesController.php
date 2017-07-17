<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends Controller
{

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
            return $this->render('pages/search.html.twig');
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
        ));
    }

    /**
     *
     * @route("/results/{page}", name="app_results")
     */
    public function resultAction($page){
        $em = $this->getDoctrine()->getRepository('AppBundle:Taxref');
        $list = $em->findBirdByLetterLimited($page , 50);

        $pagination = array(
            'page' => $page,
            //'nbPages' => $list(count($list) / 50),
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
            $list = [];
            foreach($requete as $bird){
               $number = $numberRequest->getNumberOfObservationsByBird($bird);
               $list[] = ['value' => $bird->getNomVern(), 'number' => $number, 'id' => $bird->getCdNom()];
                if($bird->getNomVern() === ''){
                    array_push($list, $bird->getlbNom());
                }
            }
            return new JsonResponse(array('list' => $list));
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

}