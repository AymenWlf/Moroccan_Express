<?php

namespace App\Controller;

use App\class\Mailjet;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        
        // $mail = new Mailjet();
        // $mail->send('alinzgohi@gmail.com','Zineb','Test','WAAAAA KEN HEBEK');

        // $products = $this->entityManager->getRepository(Product::class)->findAll();

        // $isBest_product = [];

        // foreach ($products as $product) {
        //     if ($product->getIsBest()) {
        //         $isBest_product[] = $product;

        //     }
            
        // }

        $isBest_product = $this->entityManager->getRepository(Product::class)->findBy(['isBest' => 1]);
        // dd($isBest_product);
        return $this->render('home/index.html.twig',[
            'products' => $isBest_product
        ]);
    }
}
