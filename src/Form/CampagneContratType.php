<?php

namespace App\Form;

use App\Entity\CampagneContrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampagneContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numEtage',IntegerType::class,[
                'label' => false
            ])
            ->add('numAppart',IntegerType::class,[
                'label' => false
            ])
            ->add('nbrePiece',IntegerType::class,[
                'label' => false
            ])
            ->add('prix',NumberType::class,[
                'label' => false
            ])
            ->add('details',TextType::class,[
                'label' => false
            ])
            /*->add('dateLimite')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CampagneContrat::class,
        ]);
    }
}
