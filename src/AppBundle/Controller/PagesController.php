<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends Controller {

    /**
     * @route("/search", name="app_search")
     */
    public function searchAction(Request $request){
        $em = $this->getDoctrine()->getRepository('AppBundle:Taxref');
        $list = $em->findAll();

        if($request->isMethod('POST')){
            $bird = $em->findBirdByLetter($_POST['search']);
            return $this->render('pages/search.html.twig', array(
                'bird' => $bird,
            ));
        }
        return $this->render('pages/search.html.twig', array(
            'list'=> $list,
        ));
    }

    /**
     * @route("/autocomplete", name="app_autocomplete")
     */
    public function autocompleteAction(Request $request){
        if($request->isXmlHttpRequest()){
            $letter = $request->get('bird');
            $requete = $this->getDoctrine()->getRepository('AppBundle:Taxref')->findBirdByLetter($letter);
            $list = array();
            foreach($requete as $bird){
                array_push($list, $bird->getNomVern());
                if($bird->getNomVern() === ''){
                    array_push($list, $bird->getNomValide());
                }
            }
            return new JsonResponse(array('list' => $list));
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