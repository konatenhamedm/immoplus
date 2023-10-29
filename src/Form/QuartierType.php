<?php

namespace App\Form;

use App\Entity\Quartier;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuartierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('LibQuartier')
            ->add('ville',EntityType::class, [
        'placeholder' => '----',
        'class' => Ville::class,
        'choice_label' => 'lib_ville',
        'label' => 'Ville',
        'attr' => ['class' => 'has-select2 form-select']
    ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quartier::class,
        ]);
    }
}
