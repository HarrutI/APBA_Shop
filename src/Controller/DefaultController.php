<?php

namespace App\Controller;
use App\Entity\Users;
use Symfony\Bundle\SecurityBundle\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        // Comprobar si el usuario tiene una sesión iniciada
        if ($this->getUser()) {
            // Si tiene sesión, redirigir a la página /shop/home
            return $this->redirectToRoute('app_shop_home');
        } else {
            // Si no tiene sesión, redirigir a la página /login
            return $this->redirectToRoute('app_login');
        }
    }

}
