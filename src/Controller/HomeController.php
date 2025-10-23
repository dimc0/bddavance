<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{


    #[Route('/', name: 'home')]
    public function renderHomePage(Request $request, Connection $connection, SessionInterface $session): Response
    {
        $email = $password = null;
        $error = null;

        // Si déjà connecté
        if ($session->get('userType')) {
            return $this->redirectToRoute('app_products_index');
        }

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $admin = $connection->fetchAssociative(
                'SELECT * FROM admin WHERE email = ? AND password = ?',
                [$email, $password]
            );

            $client = $connection->fetchAssociative(
                'SELECT * FROM client WHERE email = ? AND password = ?',
                [$email, $password]
            );

            if ($admin) {
                $session->set('userType', 'admin');
                $session->set('userData', $admin);
                return $this->redirectToRoute('app_products_index');
            }

            if ($client) {
                $session->set('userType', 'client');
                $session->set('userData', $client);
                return $this->redirectToRoute('app_products_index');
            }

            $error = 'Email ou mot de passe incorrect.';
        }

        return $this->render('products/home.html.twig', [
            'error' => $error,
            'email' => $email,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('home');
    }
}
