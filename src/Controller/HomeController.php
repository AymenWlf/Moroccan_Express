<?php

namespace App\Controller;

use App\class\Mailjet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        
        // $mail = new Mailjet();
        // $mail->send('alinzgohi@gmail.com','Zineb','Test','WAAAAA KEN HEBEK');
        
        return $this->render('home/index.html.twig');
    }
}
