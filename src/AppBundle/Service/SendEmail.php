<?php
namespace AppBundle\Service;

use AppBundle\Entity\Contact;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RequestStack;

class SendEmail
{

    private $mailer;
    private $templating;
    private $container;
    private $request;
    public function __construct($mailer, $templating, Container $container, RequestStack $request)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->container = $container;
        $this->request = $request;
    }

    /**
     * @param Contact $contact
     * Envoi de l'email de contact
     */
    public function sendEmailContact(Contact $contact)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setSubject('Contact NAO | ' . $contact->getSubject())
            ->setFrom($contact->getEmail())
            ->setTo('nao.openwings@gmail.com')
            ->setBody($this->templating->render(
                ':contact:contactMessage.html.twig',
                array(
                    'contact' => $contact,
                )),
                'text/html'
            );
        var_dump($message->getTo());

        $this->mailer->send($message);
    }
}