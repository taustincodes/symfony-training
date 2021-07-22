<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Service\basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class collabsController extends AbstractController
{
    /**
     * @Route("/collabs")
     */
    public function collabs(SessionInterface $session)
    {
        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);

        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('collabs.html.twig', [
            'image1' => 'images/1.png',
            'image2' => 'images/2.png',
            'image3' => 'images/3.png',
            'image4' => 'images/4.png',
            'image5' => 'images/5.png',
            'image6' => 'images/6.png',
            'promotions' => $promotions,
            "basketQuantity" => $basketQuantity
        ]);
    }
}
