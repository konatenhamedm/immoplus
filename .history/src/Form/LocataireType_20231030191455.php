<?php

namespace App\Form;

use App\Entity\Locataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NPrenoms')
            
        ->add('DateNaiss', DateType::class,  [
            'attr' => ['class' => 'datepicker no-auto skip-init'], 'widget' => 'single_text', 'format' => 'dd/MM/yyyy',
            'label' => "Date de Naissance", 'empty_data' => date('d/m/Y'), 'required' => false, 'html5' => false
        ])
            ->add('LieuNaiss', TextType::class, [
                'label' => 'Lieu de Naissance',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
            ->add('LieuNaiss', TextType::class, [
                'label' => 'Lieu de Naissance',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
            
            ->add('InfoPiece')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('ScanPiece')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('Profession')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('Ethnie')
            ->add('LieuNaiss', TextType::class, [
                'label' => 'Lieu de Naissance',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
            ->add('NbEnfts')
            ->add('NbEnfts', TextType::class, [
                'label' => 'Nbre',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
        
        ->add('NbPersChge', NumberType::class, [
            'label' => 'Nbre Personne Charge',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            
            ->add('Pere', TextType::class, [
                'label' => 'Père',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
            
        
            ->add('Mere', TextType::class, [
                'label' => 'Mère',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
        ->add('Contacts', TextType::class, [
            'label' => 'Contact',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            
            ->add('Email', EmailType::class, [
                'label' => 'Email',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
         
        ->add('NPConjointe', TextType::class, [
            'label' => 'NPConjointe',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
        ->add('ProfConj', TextType::class, [
            'label' => 'Profession Conjointe',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
        ->add('EthnieConj', TextType::class, [
            'label' => 'Ethnie Conjointe',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
     
        ->add('ContactConj', TextType::class, [
            'label' => 'Contact Conjointe',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('Genre')
            ->add('Genre', TextType::class, [
                'label' => 'Genre',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
         
        ->add('VivezAvec', TextType::class, [
            'label' => 'Vivez avec ',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            
        ->add('situationMatri', TextType::class, [
            'label' => 'Situation Matrimoniale',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Locataire::class,
        ]);
    }
}
