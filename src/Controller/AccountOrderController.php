<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/mes-commandes', name: 'account_order')]
    public function index(): Response
    {

        $orders = $this->entityManager->getRepository(Order::class)->findBySuccess($this->getUser());

        
        return $this->render('account/orders.html.twig', [
           'orders' => $orders
        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'account_order_show')]
    public function show($reference): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }
        
        return $this->render('account/order_show.html.twig', [
           'order' => $order
        ]);
    }

    #[Route('/compte/mes-commandes/annulee/{reference}', name: 'account_order_cancel')]
    public function cancel($reference): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
       


        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }
         $order->setState(6);
         $this->entityManager->flush();
         
        
        return $this->redirectToRoute('account_order');
    }

    #[Route('/compte/mes-commandes/supprimer/{reference}', name: 'account_order_remove')]
    public function remove($reference): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
       


        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }
         $this->entityManager->remove($order);
         $this->entityManager->flush();
         
        
        return $this->redirectToRoute('account_order');
    }
}
