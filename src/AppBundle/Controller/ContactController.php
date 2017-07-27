<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller {


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/contact", name="app_contact")
     */
    public function messageAction(Request $request){
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $session = $request->getSession();
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $this->get('app.envoi_email')->sendEmailContact($contact);
            $session->getFlashBag()->add('confirm-message', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('contact/contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
