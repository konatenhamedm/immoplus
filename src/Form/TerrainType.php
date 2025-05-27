<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Terrain;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $etat = $options['etat'];
        $builder
             
            ->add('num', TextType::class,[
                'label' => 'Numero ILOT',
                'attr' =>['placeholder' => 'Numero ILOT']
            ])
            ->add('superfice', TextType::class,[
                'label' => 'La superficie du terrain',
                'attr' =>['placeholder' => 'La superficie du terrain']
            ])
            // ->add('etat')
            ->add('prix', TextType::class,[
                'label' => 'Le prix de vente du terrain',
                'attr' =>['placeholder' => 'Le prix de vente du terrain']
            ])
         ->add('site', EntityType::class, [
                'class'        => Site::class,
                'label'        => 'Le site',
                'choice_label' => 'nom',
                'multiple'     => false,
                'expanded'     => false,
                'placeholder'  => 'Sélectionner un site du terrain',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                            ->where('s.etat = :etat')
                            ->setParameter('etat', 'approuve')
                            ->orderBy('s.nom', 'ASC');
            },
    'attr' => [
        'class' => 'has-select2',
        'autocomplete' => true,
    ],
])


        ;
        // ->add('justification')



        if ($etat == 'create') {
            $builder->add('nomcl', TextType::class,[
                'label' => false,
                'attr' =>['placeholder' => 'Nom du client','readonly' => true, 'hidden' => true]
                ])

                ->add('telcl', TextType::class,[
                    'label' => false,
                    'attr' =>['placeholder' => 'Telephone du client','readonly' => true, 'hidden' => true]
                ])
                ->add('localisationClient', TextType::class,[
                    'label' => false,
                    'attr' =>['placeholder' => 'Ville/village du client','readonly' => true, 'hidden' => true]
                ])
                //  ->add('rejeter', SubmitType::class,['label' => "Rejeter", 'attr' =>['class' => 'btn btn-main btn-ajax rejeter invisible ']])
                ->add('vendre', SubmitType::class,['label' => 'Valider l\achat','attr' =>['class' => 'btn btn-main btn-ajax vendu invisible  ']]);
        }
        if ($etat == 'disponible') {
            $builder->add('nomcl', TextType::class,[
                    'label' => 'Nom du client',
                    'attr' =>['placeholder' => 'Nom du client']
                ])

                ->add('telcl', TextType::class,[
                    'label' => 'Telephone du client',
                    'attr' =>['placeholder' => 'Telephone du client']
                ])
                ->add('localisationClient', TextType::class,[
                    'label' => 'Ville/village du client',
                    'attr' =>['placeholder' => 'Ville/village du client']
                ])
                //  ->add('rejeter', SubmitType::class,['label' => "Rejeter", 'attr' =>['class' => 'btn btn-main btn-ajax rejeter invisible ']])
                ->add('vendre', SubmitType::class,['label' => 'Valider l\achat','attr' =>['class' => 'btn btn-main btn-ajax vendu  ']]);
        }


        if ($etat == 'vendu') {
            $builder->add('nomcl', TextType::class,[
                    'label' => 'Nom du client',
                    'attr' =>['placeholder' => 'Nom du client', 'readonly' => true]
                ])

                ->add('telcl', TextType::class,[
                    'label' => 'Telephone du client',
                    'attr' =>['placeholder' => 'Telephone du client', 'readonly' => true,]
                ])
                ->add('localisationClient', TextType::class,[
                    'label' => 'Ville/village du client',
                    'attr' =>['placeholder' => 'Ville/village du client', 'readonly' => true,]
                ])
                //  ->add('rejeter', SubmitType::class,['label' => "Rejeter", 'attr' =>['class' => 'btn btn-main btn-ajax rejeter invisible ']])
                ->add('vendre', SubmitType::class,['label' => 'Valider l\achat', 'attr' =>['class' => 'btn btn-main btn-ajax vendu invisible ']]);
        }
    }





    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terrain::class,

        ]);
        $resolver->setRequired('etat');
    }
}
