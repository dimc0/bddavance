<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Products;
use App\Entity\ProductsOrders;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/cart')]
class OrderController extends AbstractController
{
    #[Route('/add/{id}', name: 'app_cart_add')]
    public function add(Products $product, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $userData = $session->get('userData');
        if (!$userData) {
            $this->addFlash('error', 'Vous devez être connecté pour ajouter au panier.');
            return $this->redirectToRoute('home');
        }

        // Récupérer l'entité client depuis la BDD
        $client = $em->getRepository(Client::class)->find($userData['id']);

        // Chercher le panier en cours
        $order = $em->getRepository(Order::class)->findOneBy([
            'client' => $client,
            'status' => 'pending'
        ]);

        // Si aucun panier, le créer
        if (!$order) {
            $order = new Order();
            $order->setClient($client);
            $order->setStatus('pending');
            $em->persist($order);
        }

        // Ajouter le produit au panier
        $productsOrder = new ProductsOrders();
        $productsOrder->setOrder($order);
        $productsOrder->setProduct($product);
        $productsOrder->setQuantity(1);

        $em->persist($productsOrder);
        $order->addProductsOrder($productsOrder);

        $em->flush();

        $this->addFlash('success', $product->getName() . ' a été ajouté au panier.');
        return $this->redirectToRoute('app_products_index');
    }

    #[Route('/', name: 'app_cart_index')]
    public function index(EntityManagerInterface $em, SessionInterface $session): Response
    {
        $userData = $session->get('userData');
        if (!$userData) {
            $this->addFlash('error', 'Vous devez être connecté pour voir le panier.');
            return $this->redirectToRoute('home');
        }

        $client = $em->getRepository(Client::class)->find($userData['id']);

        $order = $em->getRepository(Order::class)->findOneBy([
            'client' => $client,
            'status' => 'pending'
        ]);

        return $this->render('cart/index.html.twig', [
            'order' => $order
        ]);
    }
}
