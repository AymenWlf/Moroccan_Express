<?php

namespace App\Controller;

use App\class\Mailjet;
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
        $mail = new Mailjet();
        
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if (!$search_email) {
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $entityManager= $this->getDoctrine()->getManager();
                $entityManager -> persist($user);
                $entityManager -> flush();
                
                $mail->send_register_confirm($user->getEmail(),$user->getFirstname());

                $notif = 'Inscription reussis ! Veuillez confirmer votre email';
            }else{
                $notif = 'Votre email existe dejà dans note base de donnée !';
            }

            

          
        }else{
            $notif = 'Inscription echoue !';
        }

        return $this->render('register/index.html.twig',[
            'form' => $form->createView(),
            'notif' => $notif
        ]);
    }
}
