<?php

namespace App\Controller;

use Stripe\Stripe;
use App\class\Cart;
use App\Entity\Order;
use App\Entity\Product;

use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(Cart $cart,$reference,EntityManagerInterface $entityManagaer): Response
    {
        
        
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8000/';
        

        $order = $entityManagaer->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }
        // dd($order->getOrderDetails()->getValues());
        foreach ($order->getOrderDetails()->getValues() as $product) {
            // dd($product);
            $name = $product->getProduct();
            $product_object = $entityManagaer->getRepository(Product::class)->findOneBy(['name'=> $name]);
            $product_for_stripe[] = [
                        'price_data' => [
                            'currency' => 'mad',
                            'unit_amount' => $product->getPrice(),
                            'product_data' => [
                                'name' => $product->getProduct(),
                                'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()]
                            ],
                        ],
                        'quantity' => $product->getQuantity(),
            ];
        }

        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'mad',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN]
                ],
            ],
            'quantity' => 1,
];


Stripe::setApiKey('sk_test_51JVxlsAAzrhmOEomb2NkhflsndnEyWaopcbZSK4mEL8d0WWPvy3T08PGP09h3bXTtd311A2Q4OGjXsFR8Z6yCS8w00sKP3uRQd');
       
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [
                $product_for_stripe
            ],
            'payment_method_types' => [
              'card',
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . 'commande/merci/{CHECKOUT_SESSION_ID}/'.$reference,
            'cancel_url' => $YOUR_DOMAIN . 'commande/erreur/{CHECKOUT_SESSION_ID}/'.$reference,
            
          ]);
          
        // dd($checkout_session);
        $myId =  $checkout_session->id;
        //   $order->setStripeSessionId($myId);
        //   dump($checkout_session->id);
        //   $entityManagaer->persist($order);
        //   $entityManagaer->flush();
        //   dd($entityManagaer);
          
          $response = new JsonResponse(['id' => $myId ]);
          return $response;
          
    }
}
