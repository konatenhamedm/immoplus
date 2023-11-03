<?php

namespace App\Form;

use App\Entity\CampagneContrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('proprietaire',TextType::class,[
                'label' => false
            ])
            ->add('locataire',TextType::class,[
                'label' => false
            ])
            ->add('maison',TextType::class,[
                'label' => false
            ])
            ->add('loyer',NumberType::class,[
                'label' => false
            ])
            ->add('numAppartement',TextType::class,[
                'label' => false
            ]) ->add('dateLimite',DateType::class,  [
                'label'=>false,
                'attr' => ['class' => 'datepicker no-auto skip-init']
                , 'widget' => 'single_text'
                , 'format' => 'dd/MM/yyyy'
                , 'empty_data' => date('d/m/Y')
                , 'required' => false
                , 'html5' => false
            ])
            /*->add('locataire_hide',HiddenType::class,[
                'label' => false,
                'mapped'=>false
            ])*/

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
