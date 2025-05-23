<?php

namespace App\Controller;

use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public const LOGIN_ROUTE = 'app_login';

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): String
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');

    }

    #[Route('/access-denied', name: 'app_access_denied')]
    public function accessDenied(): Response
    {

//        $token = $this->tokenStorage->getToken();
//        $exception = $token?->getAttribute('exception');
//
//        return $this->render('security/access_denied.html.twig', [
//            'exception' => $exception,
//        ], new Response('', Response::HTTP_FORBIDDEN));
//    }


        return $this->render('security/access_denied.html.twig', [], new Response('', 403));
    }
}
