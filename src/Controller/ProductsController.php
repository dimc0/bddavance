<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Entity\Order;
use App\Entity\ProductsOrders;
use App\Entity\Client;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/products')]
final class ProductsController extends AbstractController
{
    #[Route(name: 'app_products_index', methods: ['GET'])]
    public function index(
        ProductsRepository $productsRepository,
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ): Response
    {
        $usertype = $session->get('userType', 'client'); // par défaut client
        $userData = $session->get('userData');
        $user = null;

        if ($userData && isset($userData['id'])) {
            $user = $entityManager->getRepository(Client::class)->find($userData['id']);
        }

        if ($usertype === "admin") {
            return $this->render('products/indexadmin.html.twig', [
                'products' => $productsRepository->findAll(),
            ]);
        } else {
            // Vérifie si un panier existe déjà pour le client
            if ($user) {
                $cart = $entityManager->getRepository(Order::class)
                    ->findOneBy(['client' => $user, 'status' => 'panier']);

                // Crée le panier seulement s'il n'existe pas
                if (!$cart) {
                    $cart = new Order();
                    $cart->setClient($user);
                    $cart->setStatus('panier');
                    $entityManager->persist($cart);
                    $entityManager->flush();
                }
            }

            return $this->render('products/indexclient.html.twig', [
                'products' => $productsRepository->findAll(),
            ]);
        }
    }

    #[Route('/add-to-cart/{id}', name: 'app_products_add_to_cart', methods: ['POST'])]
    public function addToCart(
        Products $product,
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ): Response
    {
        $userData = $session->get('userData');
        if (!$userData) {
            return $this->redirectToRoute('home');
        }

        $client = $entityManager->getRepository(Client::class)->find($userData['id']);

        // Récupère le panier actif du client
        $cart = $entityManager->getRepository(Order::class)->findOneBy([
            'client' => $client,
            'status' => 'panier',
        ]);

        if (!$cart) {
            $cart = new Order();
            $cart->setClient($client);
            $cart->setStatus('panier');
            $cart->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($cart);
        }

        // Vérifie si le produit est déjà dans le panier
        $existingProductOrder = $entityManager->getRepository(ProductsOrders::class)
            ->findOneBy(['order' => $cart, 'product' => $product]);

        if ($existingProductOrder) {
            $existingProductOrder->setQuantity($existingProductOrder->getQuantity() + 1);
        } else {
            $productOrder = new ProductsOrders();
            $productOrder->setOrder($cart);
            $productOrder->setProduct($product);
            $productOrder->setQuantity(1);
            $entityManager->persist($productOrder);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_products_index');
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            foreach ($product->getProductsOrders() as $productsOrder) {
                $entityManager->remove($productsOrder);
            }
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }
}
