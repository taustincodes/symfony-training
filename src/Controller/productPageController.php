<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Promotion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\basket;


class productPageController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="productPage")
     */
    public function productPage(int $id, SessionInterface $session)
    {
        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);

        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        //Get product info
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        return $this->render('productPage.html.twig', [
            "product" => $product,
            "promotions" => $promotions,
            "basketQuantity" => $basketQuantity
        ]);
    }

    /**
     * @Route ("/productajax")
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function ajaxAction(Request $request, SessionInterface $session)
    {
        $productId = $request->request->get('add');

        $productId = new basket($productId, 1);
        $productId->addToBasket($session);

        return new JsonResponse(
            [
                "test" => "testString"
            ]
        );
    }
}




