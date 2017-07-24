<?php
/**
 * Created by PhpStorm.
 * User: JulienHalgand
 * Date: 18/07/2017
 * Time: 14:52
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Observation;
use AppBundle\Entity\Taxref;
use AppBundle\Form\ObservationsExistType;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ObservationsController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/observations", name="app_indexobservation")
     */
    public function indexAction(Request $request){
        return $this->render('pages/observations/index.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/observations/voir/{id}", name="app_observation")
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
     * @route("/observations/ajouter", name="app_addObservation")
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
            $observation->setUser($this->getUser());

            $em->persist($observation);
            $em->flush();
            return $this->redirectToRoute('app_observation', array('id' => $observation->getId()));
        }
        return $this->render(':crud:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @route("mesobservations", name="app_myobservations")
     */
    public function myObservationsAction(Request $request){
        return $this->render('pages/observations/myobservations.html.twig');
    }
    /**
     *
     * @route("/observations/editer/{id}", name="app_editObservation")
     */
    public function editObservationAction(Request $request){
        return $this->render('pages/observations/edit.html.twig');
    }

    /**
     *
     * @route("/observations/supprimer/{id}", name="app_deleteObservation")
     */
    public function deleteObservationAction(Request $request){
        return $this->redirectToRoute('app_indexobservation');
    }

    /**
     * @route("/observation_map", name="app_observation_map")
     */
    public function observationMap(Request $request){
        $em = $this->getDoctrine()->getRepository('AppBundle:Taxref');

        //$list = $em->findLastObservations(50);
        if($request->isXmlHttpRequest()){
            $specyId = (int)$request->get('bird');
            $req = $this->getDoctrine()->getRepository('AppBundle:Observation')->findObservationsBySpecieId($specyId);

            $list = [];
            foreach($req as $bird){
                $list[] = [$bird->getLatitude(), $bird->getLongitude(), $bird->getId()];
            }
//            return new JsonResponse(array('list' => $list));
            $response = new Response();
            $response->setContent(json_encode(
                array('list' => $list)
            ),
                array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json')
            );

            return $response;
        }
        $query = $em->createQueryBuilder('s')
            ->orderBy('s.nomVern', 'ASC')
            ->where('s.cdTaxsup > :taxsup')
            ->setParameter('taxsup', 0)
            ->getQuery();
        $species = $query->getResult();
        $speciesWithObservations = [];
        foreach ($species as $specie){
            if(count($specie->getObservations()) > 0){
                $speciesWithObservations[] = $specie;
            }
        }
        $form = $this->createForm(ObservationsExistType::class);
        return $this->render(':pages:observation_map.html.twig', array(
            'form' => $form->createView(),
            //'list' => $list,
            'speciesWithObservations' => $speciesWithObservations
        ));
    }

    /**
     * @route("/dernieres-observations", name="app_last_observations")
     */
    public function observationsLastAction(Request $request){
        $em = $this->getDoctrine()->getRepository('AppBundle:Observation');
        $list = $em->findLastObservations(12);
        return $this->render('pages/observations_last.html.twig', array(
            'list' => $list
        ));
    }
}