<?php

namespace App\class;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart 
{
    private $session;
    private $entityManager;

    public function __construct(SessionInterface $session,EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
       $cart = $this->session->get('cart',[]);

       if (!empty($cart[$id])) 
       {
            $cart[$id]++;
       }else{
           $cart[$id] = 1;
       }

       $this->session->set('cart',$cart);
    }

    public function get(){
        return $this->session->get('cart');;
    }


    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart',[]);

        unset($cart[$id]);

        return $this->session->set('cart',$cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart',[]);


        if ($cart[$id] > 1) {
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }

        return $this->session->set('cart',$cart);
    }

    public function getFull()
    {
        $cartComplete = [];

        foreach ($this->get() as $id => $quantity) {
            $cartProduct = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
            if (!$cartProduct) {
                $this->delete($id);
                continue;
            }
            $cartComplete[] = [
                'product' => $cartProduct,
                'quantity' => $quantity
            ];
        }

        return $cartComplete;
    }
}