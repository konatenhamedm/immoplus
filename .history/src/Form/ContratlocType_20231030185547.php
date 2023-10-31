<?php

namespace App\Form;

use App\Entity\Appartement;
use App\Entity\Contratloc;
use App\Entity\Locataire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratlocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateDebut')
            ->add('DateFin')
            ->add('NbMoisCaution')
            ->add('MntCaution')
            ->add('NbMoisAvance')
            ->add('MntAvance')
            ->add('MntLoyer')
            ->add('AutreInfos')
            ->add('ScanContrat')
            ->add('DateEntree')
            ->add('DateProchVers')
            ->add('MntLoyerPrec')
            ->add('MntLoyerIni')
            ->add('MntLoyerActu')
            ->add('MntArriere')
            ->add('DejaLocataire')
            ->add('StatutLoc')
            ->add('Fraisanex')
            ->add('Etat')
            ->add('TotVerse')
          
         ->add('locataire',EntityType::class, [
                'placeholder' => '----',
                'class' => Locataire::class,
                'choice_label' => 'NPrenoms',
                'label' => 'Locataire',
                'attr' => ['class' => 'has-select2 form-select']
            ])
            ->add('appartement', EntityType::class, [
                'placeholder' => '----',
                'class' => Appartement::class,
                'choice_label' => 'lib_ville',
                'label' => 'Appart',
                'attr' => ['class' => 'has-select2 form-select']
            ])
            ->add('appartement')
            ->add('Regime')
            ->add('Nature')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contratloc::class,
        ]);
    }
}
