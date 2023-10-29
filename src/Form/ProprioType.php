<?php

namespace App\Form;

use App\Entity\Proprio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProprioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPrenoms')
            ->add('contacts')
            ->add('email')
            ->add('addresse')
            ->add('numCni')
            ->add('Cni')
            ->add('nomPere')
            ->add('nomMere')
            ->add('whatsApp')
            ->add('dateNaiss')
            ->add('lieuNaiss')
            ->add('prefession')
            ->add('dateCni')
            ->add('nomPrenomsR')
            ->add('contactsR')
            ->add('emailR')
            ->add('adresseR')
            ->add('nomPrereR')
            ->add('nomMereR')
            ->add('whatsAppR')
            ->add('dateNaissR')
            ->add('lieuNaissR')
            ->add('professionR')
            ->add('dateCniR')
            ->add('lien')
            ->add('commission')
            ->add('totalDu')
            ->add('totalPaye')
            ->add('entreprise')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proprio::class,
        ]);
    }
}
