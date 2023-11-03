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
           /* ->add('entreprise', EntityType::class, [
                'placeholder' => '----',
                'class' => Locataire::class,
                'choice_label' => 'NPrenoms',
                'label' => 'Locataire',
                'attr' => ['class' => 'has-select2 form-select']
            ])*/
            ->add('joursMois', EntityType::class, [
                'placeholder' => '----',
                'class' => JoursMois::class,
                'choice_label' => 'libelle',
                'label' => 'Jour du moisp po',
                'attr' => ['class' => 'has-select2 form-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JoursMoisEntreprise::class,
        ]);
    }
}
