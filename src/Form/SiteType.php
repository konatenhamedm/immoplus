<?php

namespace App\Form;

use App\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $etat = $options['etat'];

        $builder
            // ->add('nom')
            // ->add('localisation')
            ->add('nom', TextType::class, [
                'label' => 'Nom du site',
                'attr' => ['placeholder' => 'Nom du site']
            ])
            ->add('localisation', TextType::class, [
                'label' => 'La localisation du site',
                'attr' => ['placeholder' => 'La localisation du site']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false
            ])
            // ->add('etat')
            // ->add('justification')

        ;
        if ($etat == 'create') {
            // $builder->add('justification', HiddenType::class, [
            //     'label' => ' ',
            //     "required" => false,
            //     'attr' => ['readonly' => true, 'hidden' => true]
            // ]);
        }



        if ($etat == 'en_attente') {
            // $builder->add('justification', TextareaType::class, [
            //     'label' => 'La raison du rejet du rapport',
            //     'attr' => ['readonly' => true]
            // ])
            //  ->add('rejeter', SubmitType::class, ['label' => "Rejeter", 'attr' => ['class' => 'btn btn-main btn-ajax rejeter invisible ']])
            $builder->add('approuver', SubmitType::class, ['label' => 'Approuver', 'attr' => ['class' => 'btn btn-main btn-ajax approuver  ']]);
        }


        if ($etat == 'approuver') {
            // $builder->add('justification', TextareaType::class, [
            //     'label' => ' ',
            //     "required" => false,
            //     'attr' => ['readonly' => true, 'hidden' => true]
            // ])
            $builder->add('accorder', SubmitType::class, ['label' => 'Approuver', 'attr' => ['class' => 'btn btn-main btn-ajax approuver invisible ']])
                //    ->add('rejeter', SubmitType::class, ['label' => "Rejeter", 'attr' => ['class' => 'btn btn-main btn-ajax rejeter invisible']])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
        $resolver->setRequired('etat');
    }
}
