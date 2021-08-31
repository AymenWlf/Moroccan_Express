<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Nommez votre adresse'
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Prénom'
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Nom'
            ])
            ->add('company',TextType::class,[
                'label' => 'Votre société',
                'required' => false,
                'attr' => [
                    'placeholder' => '(facultatif)'
                ]
            ])
            ->add('address',TextareaType::class,[
                'label' => 'Votre adresse'
            ])
            ->add('postal',TextType::class,[
                'label' => 'Code Postal'
            ])
            ->add('city',TextType::class,[
                'label' => 'Ville'
            ])
            ->add('country',CountryType::class,[
                'label' => 'Pays'
                
            ])
            ->add('phone',TelType::class,[
                'label' => 'Numéro de téléphone'
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'Ajouter une adresse',
                'attr' => [
                    'class' => 'btn btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
