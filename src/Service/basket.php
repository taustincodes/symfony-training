<?php

namespace App\Service;

use App\Service\session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class basket
{
    public $id;
    public $quantity;

    function __construct($id)
    {
        $this->id = $id;
    }

    public function getQuantity(){
        return $this->quantity;
    }

    public function setQuantity($quantity){
        $this->quantity = $quantity;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }


        public function addToBasket(SessionInterface $session){

            //GET BASKET ARRAY FROM SESSION
            $basket = $session->get('basket');

            //ADD TO BASKET ARRAY
            $id = $this->getId();
            if(is_null($basket)){
                $basket = array();
            }
            if (array_key_exists($id, $basket)) {
                $basket[$id]['quantity'] += 1;
            } else {
                $basket[$id] = ['quantity' => 1];
            }


            //ADD BASKET ARRAY BACK TO SESSION
            $session->set('basket', $basket);
        }

        public function removeFromBasket(SessionInterface $session){
            //GET BASKET ARRAY FROM SESSION
            $basket = $session->get('basket');

            //CHANGE VALUES
            $id = $this->getId();
            unset($basket[$id]);

            //ADD BASKET ARRAY BACK TO SESSION
            $session->set('basket', $basket);
        }

        public function quantityUp(SessionInterface $session){
            //GET BASKET ARRAY FROM SESSION
            $basket = $session->get('basket');

            //CHANGE QUANTITY
            $id = $this->getId();
            $basket[$id]['quantity'] = $basket[$id]['quantity'] + 1;

            //SET QUANTITY FOR OBJECT
            $this->setQuantity($basket[$id]['quantity']);

            //ADD BASKET ARRAY BACK TO SESSION
            $session->set('basket', $basket);
        }

        public function quantityDown(SessionInterface $session){
            //GET BASKET ARRAY FROM SESSION
            $basket = $session->get('basket');

            //CHANGE QUANTITY
            $id = $this->getId();
            $basket[$id]['quantity'] = $basket[$id]['quantity'] - 1;
            if($basket[$id]['quantity'] == 0){
                unset($basket[$id]);
            }else {
                //SET QUANTITY FOR OBJECT
                $this->setQuantity($basket[$id]['quantity']);
            }

            //ADD BASKET ARRAY BACK TO SESSION
            $session->set('basket', $basket);
        }

        public function getBasketQuantity(SessionInterface $session){
            $total = 0;
            $basket = $session->get('basket');

            if(isset($basket)){
            foreach($basket as $key => $items){
                $sub = $basket[$key]['quantity'];
                $total = $total + $sub;
            }

            }else{
                $total = 0;
            }

            return $total;
        }
        public function clearBasket(SessionInterface $session){
            //GET BASKET ARRAY FROM SESSION
            $basket = $session->get('basket');

            //CHANGE VALUES
            foreach($basket as $key => $items) {
                unset($basket[$key]);
            }

            //ADD BASKET ARRAY BACK TO SESSION
            $session->set('basket', $basket);

        }
}