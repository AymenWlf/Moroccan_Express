<?php

namespace App\Controller;




use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this -> entityManager = $entityManager;
    }
    #[Route('/inscription', name:'register')]
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $notif = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $password = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager -> persist($user);
            $entityManager -> flush();
            $notif = 'Inscription reussis !';

            return $this->redirectToRoute('app_login');
        }else{
            $notif = 'Inscription echoue !';
        }

        return $this->render('register/index.html.twig',[
            'form' => $form->createView(),
            'notif' => $notif
        ]);
    }
}
