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
use AppBundle\Form\ObservationDeleteFormType;
use AppBundle\Form\ObservationsExistType;
use AppBundle\Form\ObservationType;
use AppBundle\Form\ObservationEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Validator\Constraints\DateTime;

class ObservationsController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/observations", name="app_indexobservation")
     * @Method({"GET","HEAD"})
     */
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM AppBundle:Observation a ORDER BY a.state ASC, a.createdAt ASC";
        $qb = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            20
        );
        $observations = $pagination->getItems();
        $formsArray = [];
        foreach($observations as $observation){
            $form = $this->createForm(ObservationDeleteFormType::class, $observation);
            $formsArray[] = $form->createView();
        }
        $pagination->setTemplate('modules:pagination.html.twig');
        return $this->render('pages/observations/index.html.twig', array(
            'pagination' => $pagination,
            'formsArray' => $formsArray
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/observations/voir/{id}", name="app_observation")
     * @Method({"GET","HEAD"})
     */
    public function observationAction(Request $request){
        $observation = $this->getDoctrine()->getRepository('AppBundle:Observation')->find((int)$request->get('id'));
        if($observation === null){ throw $this->createNotFoundException('Cette observation n\'existe pas'); }
        $specy = $observation->getSpecy()->getCdNom();
        $observations = $this->getDoctrine()->getRepository('AppBundle:Observation')->findObservationsBySpecieId($specy);
        return $this->render('pages/observation.html.twig', array(
            'specy' => $specy,
            'observation' => $observation,
            'observations' => $observations
        ));
    }

    /**
     * @route("mesobservations", name="app_myobservations")
     * @Method({"GET","HEAD"})
     */
    public function myObservationsAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM AppBundle:Observation a WHERE a.user = ".$this->getUser()->getId()." ORDER BY a.createdAt DESC";
        $qb = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            20
        );
        $userObservations = $pagination->getItems();
        $formsArray = [];
        foreach($userObservations as $observation){
            $form = $this->createForm(ObservationDeleteFormType::class, $observation);
            $formsArray[] = $form->createView();
        }
        $pagination->setTemplate('modules:pagination.html.twig');
        return $this->render('pages/observations/myobservations.html.twig', array(
            'pagination' => $pagination,
            'formsArray' => $formsArray
        ));
    }

    /**
     *
     * @route("/observations/ajouter", name="app_addObservation")
     * @Method({"GET","HEAD","POST"})
     */
    public function addObservationAction(Request $request){
        $observation = new Observation();
        $form = $this->createForm(ObservationType::class, $observation);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid() ){
            $dir = $this->container->get('kernel')->getProjectDir() . '/web/img/';
            $this->container->get('app.observation_creation')->uploadImage($observation, $dir);
            $user = $this->getUser();
            $observation->setUser($user);
            if($user->getRole() == 'ROLE_USER'){
                $observation->setState(Observation::STATUS_PENDING);
                $request->getSession()->getFlashBag()->add('success', 'Observation ajoutée. Elle est maintenant en attente de validation');
            }else{
                $observation->setState(Observation::STATUS_VALIDATE);
                $request->getSession()->getFlashBag()->add('success', 'Observation ajoutée avec le status validé');
            }

            $this->container->get('app.observation_creation')->persistObservation($observation);
            return $this->redirectToRoute('app_myobservations');
        }
        elseif($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('errors', 'Il y a des erreurs dans le formulaire');
        }
        return $this->render(':crud:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @route("/observations/editer/{id}", name="app_editObservation")
     * @Method({"GET","HEAD","POST"})
     */
    public function editObservationAction(Request $request, $id){
        $roles = $this->getUser()->getRoles();

        $observation = $this->getDoctrine()->getRepository('AppBundle:Observation')->find($id);
        if($observation === null){ throw $this->createNotFoundException('Cette observation n\'existe pas'); }

        $em = $this->getDoctrine()->getManager();
        if(in_array("ROLE_USER", $roles) && !in_array("ROLE_NATURALISTE", $roles) && $observation->getState() != "validate"){
            if($observation->getUser() == $this->getUser() || in_array("ROLE_ADMIN", $roles)){
                $form = $this->createForm(ObservationType::class, $observation);

                if($request->isMethod('POST') && $form->handleRequest($request)->isValid() ){
                    $dir = $this->container->get('kernel')->getProjectDir() . '/web/img';
                    $this->container->get('app.observation_creation')->uploadImage($observation, $dir);
                    $date = $observation->getCreatedAt();
                    $observation->setUpdatedAt($date);
                    $observation->setSpecy($observation->getSpecy());
                    $observation->setUser($this->getUser());
                    $observation->setState(Observation::STATUS_PENDING);
                    $request->getSession()->getFlashBag()->add('success', 'L\'observation a bien été modifiée');
                    $em->persist($observation);
                    $em->flush();
                    if(in_array("ROLE_NATURALISTE", $roles)){
                        return $this->redirectToRoute('app_myobservations');
                    }else{
                        return $this->redirectToRoute('app_indexobservation');
                    }
                }
                return $this->render('pages/observations/edit.html.twig', array(
                    'observation'   => $observation,
                    'form'          => $form->createView()
                ));
            }else{
                $this->createAccessDeniedException('Cette observation ne vous appartient pas');
            }
        }elseif (in_array("ROLE_NATURALISTE", $roles)){
            $form = $this->createForm(ObservationEditType::class, $observation);
            if($request->isMethod('POST') && $form->handleRequest($request)->isValid() ){
                $observation = $form->getData();
                $observation->setUpdatedAt(new \DateTime());
                $observation->setSpecy($observation->getSpecy());
                $request->getSession()->getFlashBag()->add('success', 'L\'observation a bien été modifiée');
                $em->persist($observation);
                $em->flush();
                return $this->redirectToRoute('app_indexobservation');
            }

            return $this->render('pages/observations/editNaturaliste.html.twig', array(
                'observation' => $observation,
                'form'          => $form->createView()
            ));
        }else{
            $request->getSession()
                ->getFlashBag()
                ->add('error', 'Vous ne pouvez plus éditer une observation qui a été validé par un naturaliste');
            return $this->redirectToRoute('app_myobservations');
        }
    }

    /**
     *
     * @route("/observations/supprimer/{id}", name="app_deleteObservation")
     * @Method({"DELETE"})
     */
    public function deleteObservationAction(Request $request, $id){
        $roles = $this->getUser()->getRoles();

        $observation = $this->getDoctrine()->getRepository('AppBundle:Observation')->find($id);
        $em = $this->getDoctrine()->getManager();
        if(in_array("ROLE_USER", $roles) && $observation->getState() == "pending"|"review"){
            $request->getSession()->getFlashBag()->add('success', 'L\'observation a bien été supprimée');
            $dir = $this->container->get('kernel')->getProjectDir() . '\web\img';
            $this->container->get('app.delete_image')->deleteImageWhenObservationDeleted($observation, $dir);
            $em->remove($observation);
            $em->flush();
        }elseif (in_array("ROLE_NATURALISTE", $roles)){

        }else{
            $request->getSession()
                ->getFlashBag()
                ->add('error', 'Vous ne pouvez plus supprimer une observation qui a été validé par un naturaliste');
            return $this->redirectToRoute('app_myobservations');
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @route("/carte-des-observations", name="app_observation_map")
     * @Method({"GET","HEAD","POST"})
     */
    public function observationMap(Request $request){
        $em = $this->getDoctrine()->getRepository('AppBundle:Taxref');

       $list = $this->getDoctrine()->getRepository('AppBundle:Observation')->findLastObservations(4);
        if($request->isXmlHttpRequest()){
            $specyId = (int)$request->get('bird');
            $req = $this->getDoctrine()->getRepository('AppBundle:Observation')->findObservationsBySpecieId($specyId);

            $list = [];
            foreach($req as $bird){
                $list[] = [$bird->getLatitude(), $bird->getLongitude(), $bird->getId()];
            }
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
//            'list' => $list,
            'speciesWithObservations' => $speciesWithObservations
        ));
    }

    /**
     * @route("/dernieres-observations", name="app_last_observations")
     * @Method({"GET","HEAD"})
     */
    public function observationsLastAction(Request $request){
        $em = $this->getDoctrine()->getRepository('AppBundle:Observation');
        $list = $em->findLastObservations(12);
        return $this->render('pages/observations_last.html.twig', array(
            'list' => $list
        ));
    }
}