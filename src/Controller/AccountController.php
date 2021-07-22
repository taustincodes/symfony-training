<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Promotion;
use App\Service\basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(SessionInterface $session)
    {
        //Redirect to login if not logged in
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($user == "anon."){
            return $this->redirectToRoute('app_login');
        }

        //Ger order history
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $email = $currentUser->getUsername();
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findBy(
                ["email" => $email]
            );

        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);
        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('account/index.html.twig', [
            'promotions'=> $promotions,
            "basketQuantity" => $basketQuantity,
            "orders" => $orders
        ]);
    }
}
