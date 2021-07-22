<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Service\basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class homeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(SessionInterface $session)
    {
        //Get product info for gallery
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);

        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('home.html.twig', [
            "products" => $products,
            "promotions" => $promotions,
            "basketQuantity" => $basketQuantity
        ]);
    }






}