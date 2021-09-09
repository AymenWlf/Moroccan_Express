<?php

namespace App\Controller;

use App\class\Cart;
use App\class\Mailjet;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\IFTTTHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}/{reference}', name: 'order_success')]
    public function index($stripeSessionId,$reference,Cart $cart): Response
    {
        $mail = new Mailjet();
        // dd($sessionCheckoutId);
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $order->setStripeSessionId($stripeSessionId);
        //Modifier IsPaid 

        if (!$order->getIsPaid()) {
            $cart->remove();
            $order->setIsPaid(1);
            $this->entityManager->flush();
           
            $mail->send_order_confirm($order->getUser()->getEmail(),$order->getUser()->getFirstname(),$order->getTotal(),$order->getCarrierName(),$order->getReference());
        }

       
        
        // dd($order);
        return $this->render('order_success/index.html.twig',[
            'order' => $order
        ]);
    }
}
