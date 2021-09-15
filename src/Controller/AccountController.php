<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/compte', name: 'account')]
    public function index(): Response
    {
        $user = $this->getUser();
        $address = $this->entityManager->getRepository(Address::class)->findBy(['user' => $user]);
        $orders = $this->entityManager->getRepository(Order::class)->findBy(['user' => $user]);       
        // dd($orders);
        return $this->render('account/index.html.twig',[
            'user' => $user,
            'addresses' => $address,
            'orders' => $orders
        ]);
    }
}
