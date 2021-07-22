<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;



class session
{
    private $session;

    public function __construct(SessionInterface $session){
        $this->session = $session;
    }
}

































//
//
//
//
//    public function addToSession($item){
//        $this->session->set($item);
//    }
//
//    public function removeFromSession($item){
//        $this->session->remove($item);
//    }
