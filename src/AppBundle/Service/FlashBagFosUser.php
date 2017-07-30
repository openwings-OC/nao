<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use SensioLabs\Security\SecurityChecker;

class FlashBagFosUser implements LogoutSuccessHandlerInterface {


    private $session;
    private $security;

    //Nous retrouvons nos paramètres dans notre constructeur.
    public function __construct(SecurityChecker $security, Session $session)
    {
        $this->session = $session;
        $this->security = $security;
    }

    //Méthode onLogoutSuccess utilisée dans l'interface LogoutSuccessHandlerInterface.
    public function onLogoutSuccess(Request $request)
    {
        //La notification à afficher lors d'une déconnexion.
        $this->session->getFlashBag()->add('notification', 'Vous êtes déconnecté.');

        //Vous pouvez ajouter d'autres traitements dans cette méthode.

        //Nous redirigeons l'utilisateur sur la page login.
        return new RedirectResponse('login');
    }
}
