<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\ProductsOrders;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order_index', methods: ['GET'])]
    public function index(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $userData = $session->get('userData');
        if (!$userData) {
            return $this->redirectToRoute('home');
        }

        $client = $em->getRepository(Client::class)->find($userData['id']);

        $cart = $em->getRepository(Order::class)->findOneBy([
            'client' => $client,
            'status' => 'panier',
        ]);

        return $this->render('showcart.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/update/{id}', name: 'app_order_update', methods: ['POST'])]
    public function updateQuantity(Request $request, ProductsOrders $productsOrder, EntityManagerInterface $em): Response
    {
        $quantity = (int) $request->request->get('quantity', 1);
        if ($quantity < 1) {
            $em->remove($productsOrder);
        } else {
            $productsOrder->setQuantity($quantity);
        }
        $em->flush();

        return $this->redirectToRoute('app_order_index');
    }

    #[Route('/remove/{id}', name: 'app_order_remove', methods: ['POST'])]
    public function removeProduct(ProductsOrders $productsOrder, EntityManagerInterface $em): Response
    {
        $em->remove($productsOrder);
        $em->flush();

        return $this->redirectToRoute('app_order_index');
    }

    #[Route('/checkout', name: 'app_order_checkout', methods: ['POST'])]
    public function checkout(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $userData = $session->get('userData');
        if (!$userData) {
            return $this->redirectToRoute('home');
        }

        $client = $em->getRepository(Client::class)->find($userData['id']);

        $cart = $em->getRepository(Order::class)->findOneBy([
            'client' => $client,
            'status' => 'panier',
        ]);

        if ($cart) {
            $cart->setStatus('confirmed'); // ou "paid"
            $em->flush();
        }

        return $this->redirectToRoute('app_products_index');
    }
}
