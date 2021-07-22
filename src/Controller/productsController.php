<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Repository\ProductRepository;
use App\Service\basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class productsController extends AbstractController
{
    /**
     * @Route("/products/search/{term}", name="productSearch")
     * @param Request $request
     */
    public function productSearch(Request $request, ProductRepository $repository, SessionInterface $session, string $term)
    {
        //Search bar functionality
        if ($request->isMethod('POST')) {
            $searchString = $request->request->get('search');
            return $this->redirectToRoute("productSearch" , ["term" => $searchString]);
        }

        //Get search results
        $searchQuery = $term;
        $products = $repository->findLike($searchQuery);

        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);

        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('products.html.twig', [
            "products" => $products,
            "promotions" => $promotions,
            "category" => "Showing results for '$term'...",
            "basketQuantity" => $basketQuantity
        ]);
    }

    /**
     * @Route("/products/{category}")
     */
    public function products(string $category, SessionInterface $session, Request $request)
    {
        //Search bar functionality
        if ($request->isMethod('POST')) {
            $searchString = $request->request->get('search');
            return $this->redirectToRoute("productSearch" , ["term" => $searchString]);
        }

        //Get products
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findBy(
                ["Category" => "$category"]
            );

        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);

        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('products.html.twig', [
            "products" => $products,
            "promotions" => $promotions,
            "category" => $category,
            "basketQuantity" => $basketQuantity
        ]);
    }
}



