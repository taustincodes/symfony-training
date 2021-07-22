<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Service\basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class myBasketController extends AbstractController
{
    /**
     * @Route("/mybasket", name="mybasket")
     */
    public function myBasketPage(SessionInterface $session)
    {
        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);

        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        //Get product info
        $products = $this->getProductInfo();

        //Calculate total price
        $basket = $session->get('basket');
        $status = array(
            "empty" => true
        );
        if (isset($basket)){
            $total = 0;
            foreach($basket as $key => $items){
                $total = $total + ($products[$key]->getPrice() * $basket[$key]['quantity']);
            }
            if(count($basket) > 0){
                $status = array(
                    "empty" => false,
                    "price" => number_format($total, 2)
                );
            }
        }

        return $this->render('myBasket.html.twig', [
            "products" => $products,
            "promotions" => $promotions,
            "basket" => $basket,
            "status" => $status,
            "basketQuantity" => $basketQuantity
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

    /**
     * @Route ("/mybasket/remove/ajax")
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function ajaxRemoveAction(Request $request, SessionInterface $session)
    {
        $productId = $request->request->get('del');
        $returnId = $request->request->get('del');

        $productId = new basket($productId);
        $productId->removeFromBasket($session);

        return new JsonResponse(
            [
                "key" => $returnId
            ]
        );
    }

    /**
     * @Route ("/mybasket/quantityup/ajax")
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function ajaxQuantityUpAction(Request $request, SessionInterface $session)
    {
        $productId = $request->request->get('qUp');
        $returnId = $request->request->get('qUp');

        $productId = new basket($productId);
        $productId->quantityUp($session);
        $newQuantity = $productId->getQuantity();

        return new JsonResponse(
            [
                "key" => $returnId,
                "quantity" => $newQuantity
            ]
        );
    }

    /**
     * @Route ("/mybasket/quantitydown/ajax")
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function ajaxQuantityDownAction(Request $request, SessionInterface $session)
    {
        $productId = $request->request->get('qDown');
        $returnId = $request->request->get('qDown');

        $productId = new basket($productId);
        $productId->quantityDown($session);
        $newQuantity = $productId->getQuantity();
        if($newQuantity == 0){
            $action = "removed";
        }else{
            $action = "reduced";
        }

        return new JsonResponse(
            [
                "key" => $returnId,
                "quantity" => $newQuantity,
                "action" => $action
            ]
        );
    }

    /**
     * @Route ("/mybasket/updateprice/ajax")
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function updatePriceAction(Request $request, SessionInterface $session)
    {
        //Get product info
        $products = $this->getProductInfo();

        //Calculate total price
        $basket = $session->get('basket');
        $total = 0;
        foreach ($basket as $key => $items) {
            $total = $total + ($products[$key]->getPrice() * $basket[$key]['quantity']);
        }
        return new JsonResponse([
                "total" => $total
            ]
        );
    }
    /**
     * @Route ("/mybasket/getbasketquantity/ajax")
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function getBasketQuantityAjax(Request $request, SessionInterface $session){

        $basket = new basket(0);
        return new JsonResponse([
            "itemsInBasket" => $basket->getBasketQuantity($session)
        ]);
    }
}