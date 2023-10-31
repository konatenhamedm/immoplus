<?php

namespace App\Form;

use App\Entity\Locataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('LieuNaiss', TextType::class, [
                'label' => 'Lieu de Naissance',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
            ->add('NbPersChge')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('Pere')
            ->add('LieuNaiss', TextType::class, [
                'label' => 'Lieu de Naissance',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
            ->add('Mere')
            ->add('LieuNaiss', TextType::class, [
                'label' => 'Lieu de Naissance',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
            ->add('Contacts')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('Email')
            ->add('LieuNaiss', TextType::class, [
                'label' => 'Lieu de Naissance',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
            ->add('NPConjointe')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('ProfConj')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('EthnieConj')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('ContactConj')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('Genre')
            ->add('LieuNaiss', TextType::class, [
                'label' => 'Lieu de Naissance',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
        
            ->add('VivezAvec')
        ->add('LieuNaiss', TextType::class, [
            'label' => 'Lieu de Naissance',
            //'attr' => ['placeholder' => 'Nom du chef de délégation']
        ])
        
            ->add('situationMatri')
        ->add('situationMatri', TextType::class, [
            'label' => 'Lieu de Naissance',
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
