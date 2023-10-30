<?php

namespace App\Form;

use App\Entity\Proprio;
use App\Entity\Ville;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('Cni',     FichierType::class,
                [
                    /*  'label' => 'Fichier',*/
                    'label' => 'Cni',
                    'doc_options' => $options['doc_options'],
                    'required' => $options['doc_required'] ?? true,
                    'validation_groups' => $options['validation_groups'],
                ])
            ->add('nomPere')
            ->add('nomMere')
            ->add('whatsApp')
            ->add('dateNaiss',DateType::class,  [
                'attr' => ['class' => 'datepicker no-auto skip-init']
                , 'widget' => 'single_text'
                , 'format' => 'dd/MM/yyyy',
                'label'=>false
                , 'empty_data' => date('d/m/Y')
                , 'required' => false
                , 'html5' => false
            ])
            ->add('lieuNaiss')
            ->add('prefession')
            ->add('dateCni',DateType::class,  [
                'attr' => ['class' => 'datepicker no-auto skip-init']
                , 'widget' => 'single_text'
                , 'format' => 'dd/MM/yyyy',
                'label'=>false
                , 'empty_data' => date('d/m/Y')
                , 'required' => false
                , 'html5' => false
            ])
            ->add('nomPrenomsR')
            ->add('contactsR')
            ->add('emailR')
            ->add('adresseR')
            ->add('nomPrereR')
            ->add('nomMereR')
            ->add('whatsAppR')
            ->add('dateNaissR',DateType::class,  [
                'attr' => ['class' => 'datepicker no-auto skip-init']
                , 'widget' => 'single_text'
                , 'format' => 'dd/MM/yyyy',
                'label'=>false
                , 'empty_data' => date('d/m/Y')
                , 'required' => false
                , 'html5' => false
            ])
            ->add('lieuNaissR')
            ->add('professionR')
            ->add('dateCniR' ,DateType::class,  [
                'attr' => ['class' => 'datepicker no-auto skip-init']
                , 'widget' => 'single_text'
                , 'format' => 'dd/MM/yyyy',
                'label'=>false
                , 'empty_data' => date('d/m/Y')
                , 'required' => false
                , 'html5' => false
            ])
            ->add('lien',     FichierType::class,
                [
                    /*  'label' => 'Fichier',*/
                    'label' => 'Lien',
                    'doc_options' => $options['doc_options'],
                    'required' => $options['doc_required'] ?? true,
                    'validation_groups' => $options['validation_groups'],
                ])
            ->add('commission')
            ->add('totalDu')
            ->add('totalPaye')
           /* ->add('entreprise',EntityType::class, [
        'placeholder' => '----',
        'class' => Entreprise::class,
        'choice_label' => 'denomination',
        'label' => 'Entreprise',
        'attr' => ['class' => 'has-select2 form-select']
    ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proprio::class,
            'doc_required' => true,
            'doc_options' => [],
            'validation_groups' => [],
        ]);
        $resolver->setRequired('doc_options');
        $resolver->setRequired('doc_required');
        $resolver->setRequired(['validation_groups']);
    }
}
