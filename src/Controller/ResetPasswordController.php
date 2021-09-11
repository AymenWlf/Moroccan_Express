<?php

namespace App\Controller;

use App\class\Mailjet;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/reset/password', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            $this->redirectToRoute('home');
        }        
        
        if ($request->get('email')) {
            // 1- Enregistrer les infos du reset_passord sur la base de donnee
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);
            $date = new DateTimeImmutable();
            $reset_password = new ResetPassword();
            $reset_password->setUser($user);
            $reset_password->setToken(uniqid());
            $reset_password->setCreatedAt($date);
            // dd($reset_password);
            $this->entityManager->persist($reset_password);
            $this->entityManager->flush();

            // 2- Envoyez un email avec le lien de recuperation du mot de passe
            $url = $this->generateUrl('update_password',[
                'token' => $reset_password->getToken()
            ]);
            $mail = new Mailjet();
            $mail->send_reset_password($user->getEmail(),$user->getFirstname(),$url);
            $this->addFlash('notice','Un email de recuperation vient de vous etre envoyer, veuiller suivre les instructions donnee');

        }else{
            $this->addFlash('notice','Cette adresse email est inconnu');
        }


        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/reset/password/{token}', name: 'update_password')]
    public function update(Request $request,$token,UserPasswordEncoderInterface $encoder)/*: Response*/
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

        if (!$reset_password) {
            $this->redirectToRoute('reset_password');
        }

        $now = new DateTimeImmutable();
        $date_reset = $reset_password->getCreatedAt()->modify('+ 30 minutes');
        if ($date_reset < $now) {
            
            $this->addFlash('notice','Votre demande de recuperation de mot de passe a expire, Merci de la renouveller');
            $this->redirectToRoute('reset_password');
        }


        // Modifier son mot de passe grace a une vue
        $user = $reset_password->getUser();
        $form = $this->createForm(ResetPasswordType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $new_pwd = $data->getPassword();
            $password = $encoder->encodePassword($user , $new_pwd);
            $user->setPassword($password);
            $this->entityManager->flush();
            $this->addFlash('notice','Votre mot de passe a ete renouveler avec succes');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig',[
            'form' => $form->createView()
        ]);
        // Encodage et flush

        // dd($reset_password);
    }
}
