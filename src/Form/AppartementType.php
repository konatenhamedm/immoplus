<?php

namespace App\Form;

use App\Entity\Appartement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppartementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('LibAppart', TextType::class, [

                'label' => "N° Appartement"
            ])
            ->add('NbrePieces', IntegerType::class, [
                'label' => "Nbre Pice"
            ])
            ->add('NumEtage', IntegerType::class, [
                'label' => "N° Etage "
            ])
            ->add('Loyer', NumberType::class, [
                'label' => "Loyer"
            ])
            /* ->add('Caution')*/
            ->add('Details', TextType::class, [
                'label' => "Autres Détails",
                "empty_data" => 'RAS',
            ])
            /*->add('Oqp')*/
            /*->add('maisson')*/;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appartement::class,
        ]);
    }
}
