<?php

namespace App\Controller;

use DateTime;
use App\class\Cart;
use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'order')]
    public function index(Cart $cart,Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }
        $form = $this->createForm(OrderType::class,null,[
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        return $this->render('order/index.html.twig',[
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap')]
    public function add(Cart $cart,Request $request): Response
    {
        
        $form = $this->createForm(OrderType::class,null,[
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTimeImmutable();
            $delivery = $form->get('addresses')->getData();
            $carrier = $form->get('carriers')->getData();
            // dd($form->getData());
            $delivery_content = $delivery->getFirstname().'  '.$delivery->getLastname();
            $delivery_content .='<br/>'.$delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .='<br/>'.$delivery->getCompany();
            }

            $delivery_content .='<br/>'.$delivery->getAddress();
            $delivery_content .='<br/>'.$delivery->getPostal().' - '.$delivery->getCity().' - '.$delivery->getCountry();

            // dd($delivery_content);
            
            // Enregistrer ma commande Order()
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);
            $this->entityManager->persist($order);

            // dd($order);


            // Enregistrer mes produits OrdertDetails()
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
                
            }

           $this->entityManager->flush();
           
           return $this->render('order/add.html.twig',[
            'cart' => $cart->getFull(),
            'carrier' => $carrier,
            'delivery' => $delivery_content
        ]);
        }

        return $this->redirectToRoute('cart');
        
    }
}
