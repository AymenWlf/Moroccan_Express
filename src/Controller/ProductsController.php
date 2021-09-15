<?php

namespace App\Controller;

use App\class\Cart;
use App\class\Search;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request,Cart $cart): Response
    {
        

        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }else{
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }


        return $this->render('products/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug,Cart $cart): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        $isBest_product = $this->entityManager->getRepository(Product::class)->findBy(['isBest' => 1]);

        if (!$product) {
            return $this->redirectToRoute('products');
        }


        return $this->render('products/show_product.html.twig', [
            'product' => $product,
            'products' => $isBest_product,
            'cart' => $cart->getFull()
        ]);
    }
}
