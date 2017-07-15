<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        return $this->render('pages/homepage.html.twig');

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
            $list = [];
            foreach($requete as $bird){
                $list[] = ['value' => $bird->getNomVern(), 'id' => $bird->getCdNom()];
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
     * @route("/observation/{id}", name="app_observation")
     */
    public function observationAction(Request $request){

        return $this->render('pages/observation.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    public function specieAction(Request $request){

    }


}
