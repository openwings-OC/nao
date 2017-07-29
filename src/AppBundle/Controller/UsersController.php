<?php
/**
 * Created by PhpStorm.
 * User: JulienHalgand
 * Date: 19/07/2017
 * Time: 20:05
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use AppBundle\Form\UserDeleteFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UsersController extends Controller
{
    /**
     * @Route("/users", name="app_indexuser")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM AppBundle:User a";
        $qb = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            20
        );
        $users = $pagination->getItems();
        $formsArray = [];
        $formsBlockArray = [];
        foreach($users as $user){
            $form = $this->createForm(UserDeleteFormType::class, $user);
            $formsArray[] = $form->createView();
            $formBlock = $this->createForm(UserDeleteFormType::class, $user);
            $formsBlockArray[] = $formBlock->createView();
        }
        $pagination->setTemplate('modules:pagination.html.twig');
        return $this->render('pages/users/index.html.twig', array(
            'pagination' => $pagination,
            'formsArray' => $formsArray,
            'formsBlockArray' => $formsBlockArray
        ));
    }
    /**
     * @Route("/users/editer/{id}", name="app_edituser")
     */
    public function editAction(Request $request, $id)
    {
        $roles = $this->getUser()->getRoles();

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $em = $this->getDoctrine()->getManager();
            $form = $this->createForm(UserEditType::class, $user);

            if($request->isMethod('POST') && $form->handleRequest($request)->isValid() ){
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('app_indexuser');
            }
            return $this->render('pages/users/edit.html.twig', array(
                'observation'   => $user,
                'form'          => $form->createView()
            ));
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Le rôle de '.$user->getUsername().' a été modifié avec succès.', 'Vous ne pouvez plus éditer une observation qui a été validé par un naturaliste');
            return $this->redirectToRoute('app_indexuser');
    }
    /**
     * @Route("/users/supprimer/{id}", name="app_deleteuser")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $flashmessage = $user->getUsername()." a bien été supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $request->getSession()
            ->getFlashBag()
            ->add('success', $flashmessage);
        return $this->redirectToRoute('app_indexuser');
    }
    /**
     * @Route("/users/enable/{id}", name="app_enableuser")
     * @Method({"POST"})
     */
    public function enableAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $flashmessage = "";

        if($user->getEnabled()){
            $user->setEnabled(false);
            $flashmessage = $user->getUsername()." a bien été bloqué et ne pourra plus avoir accès au site";
        }else{
            $user->setEnabled(true);
            $flashmessage = $user->getUsername()." a bien été débloqué et pourra de nouveau accèder au site";
        }
        $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', $flashmessage);
            return $this->redirectToRoute('app_indexuser');
    }
}