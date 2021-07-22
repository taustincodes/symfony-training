<?php

namespace App\Controller;
use App\Entity\Promotion;
use App\Service\basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class aboutUsController extends AbstractController
{
    /**
     * @Route("/aboutUs")
     */
   public function aboutUs(SessionInterface $session)
   {
       //Header
       $basket = new basket(0);
       $basketQuantity = $basket->getBasketQuantity($session);
       $promotions = $this->getDoctrine()
           ->getRepository(Promotion::class)
           ->findAll();

       return $this->render('aboutUs.html.twig', [
           'promotions'=> $promotions,
           "basketQuantity" => $basketQuantity

       ]);
   }

}