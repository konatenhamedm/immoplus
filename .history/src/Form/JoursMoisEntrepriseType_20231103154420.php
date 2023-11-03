<?php

namespace App\Form;

use App\Entity\JoursMoisEntreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoursMoisEntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('entreprise')
            ->add('joursMois')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JoursMoisEntreprise::class,
        ]);
    }
}
