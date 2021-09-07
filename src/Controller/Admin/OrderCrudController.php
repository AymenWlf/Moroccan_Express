<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index' , 'detail');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('reference','Référence'),
            DateTimeField::new('createdAt','Passée le'),
            TextField::new('user.getFullname','Utilisateur'),
            TextField::new('carrierName','Livreur'),
            MoneyField::new('carrierPrice','Frais de port')->setCurrency('MAD'),
            BooleanField::new('isPaid','Payée'),
            MoneyField::new('total','Totals')->setCurrency('MAD'),
            ArrayField::new('orderDetails','Produit Achetée')

        ];
    }
    
}
