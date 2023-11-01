<?php

namespace App\Form;

use App\Entity\Appartement;
use App\Entity\Locataire;
use App\Entity\Contratloc;
use App\Entity\Tabmois;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;

class ContratlocType extends AbstractType
{
    private $groupe;
    private $entreprise;
    public function __construct(Security $security){
        $this->groupe = $security->getUser()->getGroupe()->getCode();
        $this->entreprise = $security->getUser()->getEmploye()->getEntreprise();
    }
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
           ->add('ScanContrat',
            FichierType::class,
            [
                /*  'label' => 'Fichier',*/
                'label' => 'Lien social ou famillial',
                'doc_options' => $options['doc_options'],
                'required' => $options['doc_required'] ?? true,
                'validation_groups' => $options['validation_groups'],
            ]
        )
    
        ->add('DateEntree', DateType::class,  [
            'attr' => [
                'class' => 'datepicker no-auto skip-init'
            ],
            'widget' => 'single_text', 'format' => 'dd/MM/yyyy',
            'label' => "Date d'entree",
            'empty_data' => date('d/m/Y'),
            'required' => false,
            'html5' => false
        ])
        ->add('DateProchVers', DateType::class,  [
            'attr' => [
            'class' => 'datepicker no-auto skip-init'],
             'widget' => 'single_text', 'format' => 'dd/MM/yyyy',
            'label' => "Date du prochain versement",
             'empty_data' => date('d/m/Y'), 
             'required' => false, 
             'html5' => false
        ])
       
        

           /* ->add('dateremise', DateType::class, [
                "label" => "Date de remise",
                "required" => true,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => ['class' => 'date']
            ])*/
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
                    'multiple' => false,
                    'expanded' => true,
                   
                ),
                
                
            )
            ->add('Fraisanex')
          //  ->add('Etat')
            //->add('TotVerse')
          ->add('appart', EntityType::class, [
              'class' => Appartement::class,
              'choice_label' => 'LibAppart',
              'placeholder' => '-----',
              'label' => 'Choix de appartement ',
             /* 'choice_attr' => function (Tabmois $tabmois) {
                  return ['data-value' => $tabmois->getLibMois()];
              },*/
              'query_builder' => function (EntityRepository $er) {
                  return $er->createQueryBuilder('e')
                      ->innerJoin('e.maisson','m')
                      ->innerJoin('m.proprio','p')
                      ->andWhere('e.Oqp = :etat')
                      ->andWhere('p.entreprise = :entreprise')
                      ->setParameter('etat',0)
                      ->setParameter('entreprise',$this->entreprise)
                      ;
              },
              'attr' => ['class' => 'has-select2 form-select appart']
          ])
         ->add('locataire',EntityType::class, [
                'placeholder' => '----',
                'class' => Locataire::class,
                'choice_label' => 'NPrenoms',
                'label' => 'Locataire',
                'attr' => ['class' => 'has-select2 form-select']
            ])

         
            ->add('Regime',
                ChoiceType::class,
                [
                    'choices' => [
                    'Payé-Consommé' => 'Paye_Consomme',
                    'Consommé-Payé' => 'Consomme_Paye',

                    ],
                    'choice_value' => null,
                    'multiple' => false,
                    'expanded' => true,
                    'empty_data' => 'Payé-Consommé',
                ]
            )
            ->add(
            'Nature',
                ChoiceType::class,
                [
                    'choices'  => [
                        '....' => null,
                        'Habitation' => 'habitation',
                        'Magasin' => 'Magasin',
                    ],

                    'mapped' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'label'        => false,
                    "required" => true,
                    'attr' => ['class' => 'has-select2 changer'],
                ]
            )
         
            
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
