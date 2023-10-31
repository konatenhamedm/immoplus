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
                'label' => 'Lieu de ',
                //'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
            ->add('LieuNaiss')
            ->add('InfoPiece')
            ->add('ScanPiece')
            ->add('Profession')
            ->add('Ethnie')
            ->add('NbEnfts')
            ->add('NbPersChge')
            ->add('Pere')
            ->add('Mere')
            ->add('Contacts')
            ->add('Email')
            ->add('NPConjointe')
            ->add('ProfConj')
            ->add('EthnieConj')
            ->add('ContactConj')
            ->add('Genre')
            ->add('VivezAvec')
            ->add('situationMatri')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Locataire::class,
        ]);
    }
}
