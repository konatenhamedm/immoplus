<?php

namespace App\Form;

use App\Entity\Echancier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EchancierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class, [
                'label' => 'Date de debut ',
                'html5' => false,
                'attr' => ['class' => 'has-datepicker no-auto skip-init', 'autocomplete' => 'off'],
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'empty_data' => date('d/m/Y')
            ])
            ->add('montant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Echancier::class,
        ]);
    }
}
