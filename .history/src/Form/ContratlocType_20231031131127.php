<?php

namespace App\Form;

use App\Entity\Locataire;
use App\Form\FichierType;
use App\Entity\Contratloc;
use App\Entity\Appartement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContratlocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
          /*  ->add('DateDebut', DateType::class,  [
                'attr' => ['class' => 'datepicker no-auto skip-init'], 'widget' => 'single_text', 'format' => 'dd/MM/yyyy',
                'label' => "Date debu", 'empty_data' => date('d/m/Y'), 'required' => false, 'html5' => false
            ])
            ->add('DateDebut', DateType::class,  [
                'attr' => ['class' => 'datepicker no-auto skip-init'], 'widget' => 'single_text', 'format' => 'dd/MM/yyyy',
                'label' => "Date fin", 'empty_data' => date('d/m/Y'), 'required' => false, 'html5' => false
            ])*/
            ->add('NbMoisCaution')
            ->add('MntCaution')
            ->add('NbMoisAvance')
            ->add('MntAvance')
            //->add('MntLoyer')
            ->add('AutreInfos')
           ->add(
            'ScanContrat',
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
       
        ->add(
            'DejaLocataire',
            ChoiceType::class,
            array(
                'choices' => array(
                    'Oui' => 'oui',
                    'Nom' => 'nom',

                ),
                'choice_value' => null,
                'multiple' => false,
                'expanded' => true
            )
        )

            ->add(
            'StatutLoc',
                ChoiceType::class,
                array(
                    'choices' => array(
                    'Nouveau (elle)' => 'nouveau',
                    'Ancien(ne)' => 'ancien',

                    ),
                    'choice_value' => null,
                    'label' => 'Statut locataire :',
                    'choice_label' =>  '',
                    'multiple' => false,
                    'expanded' => true
                )
                
            )
            ->add('Fraisanex')
          //  ->add('Etat')
            //->add('TotVerse')
          
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
         
            ->add('Regime',
                ChoiceType::class,
                array(
                    'choices' => array(
                    'Payé-Consommé' => 'Paye_Consomme',
                    'Consommé-Payé' => 'Consomme_Paye',

                    ),
                    'choice_value' => null,
                    'multiple' => false,
                    'expanded' => true
                )
            )
            ->add('Nature')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       
        $resolver->setDefaults([
            'data_class' => Contratloc::class,
            'doc_required' => true,
            'doc_options' => [],
            'validation_groups' => [],
        ]);
        $resolver->setRequired('doc_options');
        $resolver->setRequired('doc_required');
        $resolver->setRequired(['validation_groups']);

        
    }
}
