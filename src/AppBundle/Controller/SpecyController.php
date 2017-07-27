<?php
/**
 * Created by PhpStorm.
 * User: JulienHalgand
 * Date: 18/07/2017
 * Time: 15:01
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Taxref;
use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SpecyController extends Controller
{
    /**
     * @route("/recherche-especes", name="app_search")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Taxref');
        if ($request->isMethod('POST')) {
            $bird = $em->findBirdByLetter($_POST['search']);
        }
        else {
            $bird = $em->findAll();
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $bird,
            $request->query->getInt('page', 1),
            Taxref::PAGE_NUMBER
        );
        $pagination->setTemplate('modules:pagination.html.twig');
        return $this->render('pages/search.html.twig', array(
            'pagination' => $pagination,
            'bird' => $bird
        ));
    }

    /**
     *
     * @route("/resultats/{page}", name="app_results")
     */
    public function resultAction($page){
        $em = $this->getDoctrine()->getRepository('AppBundle:Taxref');
        $list = $em->findBirdByLetterLimited($page , Taxref::PAGE_NUMBER);

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
     * @route("/espece/{id}", name="app_specy")
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


}