<?php

namespace App\Form;

use App\Entity\Appartement;
use App\Entity\Contratloc;
use App\Entity\Locataire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratlocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('DateDebut', DateType::class,  [
                'attr' => ['class' => 'datepicker no-auto skip-init'], 'widget' => 'single_text', 'format' => 'dd/MM/yyyy',
                'label' => "Date debu", 'empty_data' => date('d/m/Y'), 'required' => false, 'html5' => false
            ])
            ->add('DateDebut', DateType::class,  [
                'attr' => ['class' => 'datepicker no-auto skip-init'], 'widget' => 'single_text', 'format' => 'dd/MM/yyyy',
                'label' => "Date fin", 'empty_data' => date('d/m/Y'), 'required' => false, 'html5' => false
            ])
            ->add('NbMoisCaution')
            ->add('MntCaution')
            ->add('NbMoisAvance')
            ->add('MntAvance')
            ->add('MntLoyer')
            ->add('AutreInfos')
            ->add('')
        ->add(
            'lien',
            FichierType::class,
            [
                /*  'label' => 'Fichier',*/
                'label' => 'Lien social ou famillial',
                'doc_options' => $options['doc_options'],
                'required' => $options['doc_required'] ?? true,
                'validation_groups' => $options['validation_groups'],
            ]
        )
            ->add('DateEntree')
          
        ->add('DateProchVers', DateType::class,  [
            'attr' => ['class' => 'datepicker no-auto skip-init'], 'widget' => 'single_text', 'format' => 'dd/MM/yyyy',
            'label' => "Date du prochain versement", 'empty_data' => date('d/m/Y'), 'required' => false, 'html5' => false
        ])
            ->add('MntLoyerPrec')
            ->add('MntLoyerIni')
            ->add('MntLoyerActu')
            ->add('MntArriere')
            ->add('DejaLocataire')
            ->add('StatutLoc')
            ->add('Fraisanex')
            ->add('Etat')
            ->add('TotVerse')
          
         ->add('locataire',EntityType::class, [
                'placeholder' => '----',
                'class' => Locataire::class,
                'choice_label' => 'NPrenoms',
                'label' => 'Locataire',
                'attr' => ['class' => 'has-select2 form-select']
            ])
            ->add('appartement', EntityType::class, [
                'placeholder' => '----',
                'class' => Appartement::class,
                'choice_label' => 'LibAppart',
                'label' => 'Appartement',
                'attr' => ['class' => 'has-select2 form-select']
            ])
           
            ->add('Regime')
            ->add('Nature')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contratloc::class,
        ]);

        
    }
}
