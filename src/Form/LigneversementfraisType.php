<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\CompteCltT;
use App\Entity\Ligneversementfrais;
use App\Form\DataTransformer\ThousandNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneversementfraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datePaiement', DateType::class, [
                'required' => true,
                'mapped' => false,
                'widget' => 'single_text',
                'label'   => 'Date de paiement',
                'format'  => 'dd/MM/yyyy',
                'html5' => false,
                'attr'    => ['autocomplete' => 'off', 'class' => 'datepicker no-auto'],
            ])
            ->add('montant', TextType::class, ['label' => 'Montant', 'mapped' => false,
            'attr' => ['class' => 'input-money input-mnt'],
            'empty_data' => '0',]    
            )
            ->add('annuler', SubmitType::class, ['label' => 'Annuler', 'attr' => ['class' => 'btn btn-default btn-sm', 'data-bs-dismiss' => 'modal']])
            ->add('save', SubmitType::class, ['label' => 'Payer', 'attr' => ['class' => 'btn btn-primary btn-ajax btn-sm']]);
        $builder->get('montant')->addModelTransformer(new ThousandNumberTransformer());
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompteCltT::class,
        ]);
    }
}
