<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/nos-produits', name: 'products')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();




        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        if (!$product) {
            return $this->redirectToRoute('products');
        }


        return $this->render('products/show_product.html.twig', [
            'product' => $product
        ]);
    }
}
