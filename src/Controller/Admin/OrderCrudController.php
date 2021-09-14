<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager,AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    
   
    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation',"Préparation en cours" , 'fas fa-box')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery',"Livraison en cours", 'fas fa-truck')->linkToCrudAction('updateDelivery');
        $Delivried = Action::new('Delivried',"Livrée" , 'fas fa-box-open')->linkToCrudAction('Delivried');
        $Cancel = Action::new('Cancel',"Annulée", 'fas fa-minus-circle')->linkToCrudAction('Cancel');

        return $actions
            ->add('detail', $Cancel)
            ->add('detail', $Delivried)
            ->add('detail', $updateDelivery)
            ->add('detail', $updatePreparation)
            ->add('index' , 'detail');
    }

    public function Cancel(AdminContext $context)
    {
        
        $order = $context->getEntity()->getInstance();
        if ($order->getState()==5) {
            $this->addFlash('notice',"<span class=' alert-warning text-center'><strong>La commande : .".$order->getReference()." est dejà annulée</strong></span>");
        }else{
            $order->setState(5);
            $this->entityManager-> flush();

            $this->addFlash('notice',"<span class=' alert-success text-center'><strong>La commande : .".$order->getReference()." est annulée</strong></span>");
        }
            $url = $this->adminUrlGenerator
                ->setController(OrderCrudController::class)
                ->setAction('index')
                ->generateUrl();

                
            // mail
       
        return $this->redirect($url);
    }

    public function Delivried(AdminContext $context)
    {
        
        $order = $context->getEntity()->getInstance();
        if ($order->getState()==4) {
            $this->addFlash('notice',"<span class=' alert-warning text-center'><strong>La commande : .".$order->getReference()." est dejà livrée</strong></span>");
        }else{
            $order->setState(4);
            $this->entityManager-> flush();

            $this->addFlash('notice',"<span class=' alert-success text-center'><strong>La commande : .".$order->getReference()." est livrée</strong></span>");
        }
            $url = $this->adminUrlGenerator
                ->setController(OrderCrudController::class)
                ->setAction('index')
                ->generateUrl();

                
            // mail
       
        return $this->redirect($url);
    }

    public function updatePreparation(AdminContext $context)
    {
        
        $order = $context->getEntity()->getInstance();
        if ($order->getState()==2) {
            $this->addFlash('notice',"<span class=' alert-warning text-center'><strong>La commande : .".$order->getReference()." est dejà en cours de préparation</strong></span>");
        }else{
            $order->setState(2);
            $this->entityManager-> flush();

            $this->addFlash('notice',"<span class=' alert-success text-center'><strong>La commande : .".$order->getReference()." est en cours de préparation</strong></span>");
        }
            $url = $this->adminUrlGenerator
                ->setController(OrderCrudController::class)
                ->setAction('index')
                ->generateUrl();

                
            // mail
       
        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        
        $order = $context->getEntity()->getInstance();
        if ($order->getState()==3) {
            $this->addFlash('notice',"<span class=' alert-warning text-center'><strong>La commande : .".$order->getReference()." est dejà en cours de livraison</strong></span>");
        }else{
            $order->setState(3);
            $this->entityManager-> flush();

            $this->addFlash('notice',"<span class=' alert-success text-center'><strong>La commande : .".$order->getReference()." est en cours de livraison</strong></span>");
        }
            $url = $this->adminUrlGenerator
                ->setController(OrderCrudController::class)
                ->setAction('index')
                ->generateUrl();

                

       
        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('reference','Référence'),
            DateTimeField::new('createdAt','Passée le'),
            TextField::new('user.getFullname','Utilisateur'),
            TextEditorField::new('delivery','Adresse de livraison')->onlyOnDetail(),
            TextField::new('carrierName','Livreur'),
            MoneyField::new('carrierPrice','Frais de port')->setCurrency('MAD'),
            ChoiceField::new('state')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3,
                'Livrée' => 4,
                'Annulée' => 5,
                'Annulée par User' => 6
            ]),
            BooleanField::new('isPaid','Paiement'),
            MoneyField::new('total','Totals')->setCurrency('MAD'),
            ArrayField::new('orderDetails','Produit Achetée')->onlyOnDetail()

        ];
    }
    
}
