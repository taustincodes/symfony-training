<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Service\basket;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/mybasket/checkout", name="checkout")
     */
    public function index(SessionInterface $session, Request $request)
    {
        //If basket is empty redirect to account
        if($session->get('basket') == null) {
            return $this->redirectToRoute('account');
        }

        //Get basket
        $basketArray = $session->get('basket');

        //Place order (once form is submitted)
        if ($request->isMethod('POST')) {
            foreach($basketArray as $key => $items){
                $order = new Order();
                $order->setCustomerFirstName($request->request->get('firstName'));
                $order->setCustomerLastName($request->request->get('lastName'));
                $order->setAddress($request->request->get('address'));
                $order->setPostcode($request->request->get('postcode'));
                $order->setEmail($request->request->get('email'));
                $order->setDate(date("Y-m-d"));

                $order->setProductId("$key");
                $order->setQuantity($basketArray[$key]['quantity']);

                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();
            }
            return $this->redirectToRoute('orderplaced');
        }

        //Get product info
        $products = $this->getProductInfo();

        //Calculate total price
        $total = 0;
        foreach($basketArray as $key => $items){
            $total = $total + ($products[$key]->getPrice() * $basketArray[$key]['quantity']);
        }
        if(count($basketArray) == 0){
            $status = array(
                "empty" => true
            );
        }else{
            $status = array(
                "empty" => false,
                "price" => number_format($total, 2)
            );
        }

        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);

        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('checkout/checkout.html.twig', [
            "products" => $products,
            "promotions" => $promotions,
            "basket" => $basketArray,
            "status" => $status,
            "basketQuantity" => $basketQuantity,
        ]);
    }

    /**
     * @Route("/mybasket/orderplaced", name="orderplaced")
     */
    public function orderPlaced(MailerInterface $mailer, SessionInterface $session){

        //Get order info for email
        $basketArray = $session->get('basket');
        $products = $this->getProductInfo();
        $total = 0;
        foreach($basketArray as $key => $items){
            $total = $total + ($products[$key]->getPrice() * $basketArray[$key]['quantity']);
        }
        if(count($basketArray) == 0){
            $status = array(
                "empty" => true
            );
        }else{
            $status = array(
                "empty" => false,
                "price" => number_format($total, 2)
            );
        }

        //Send email
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@thet24store.com', 'The T-24 Team'))
            ->to(new Address($user->getEmail(), $user->getFirstName()))
            ->subject('Thank you for your order!')
            ->htmlTemplate('email/order.html.twig')
            ->context([
                "products" => $products,
                "basket" => $basketArray,
                "status" => $status
            ]);
        $mailer->send($email);

        //Clear basket (order is now complete)
        $basket = new basket(0);
        $basket->clearBasket($session);

        //Header
        $basketQuantity = $basket->getBasketQuantity($session);

        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('checkout/orderPlaced.html.twig', [
            "promotions" => $promotions,
            "basketQuantity" => $basketQuantity,
        ]);
    }

    public function getProductInfo(){
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
        //Reindex products array to match basket items. Basket items are keyed via the product Id, the $products array must match this by starting at 1.
        $products = array_combine(range(1, count($products)), array_values($products));

        return $products;
    }
}
