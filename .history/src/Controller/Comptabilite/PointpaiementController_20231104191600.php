<?php

namespace App\Controller\Comptabilite;

use App\Entity\Campagne;
use App\Entity\Factureloc;
use App\Entity\Maison;
use App\Form\CampagneType;
use App\Repository\AppartementRepository;
use App\Repository\CampagneRepository;
use App\Repository\FacturelocRepository;
use App\Repository\JoursMoisEntrepriseRepository;
use App\Service\ActionRender;
use App\Service\FormError;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use App\Entity\CampagneContrat;
use App\Repository\ContratlocRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/ads/comptabilite/point')]
class PointpaiementController extends BaseController
{
    const INDEX_ROOT_NAME_LOCATAIRE = 'app_point_locataire_index';
    const INDEX_ROOT_NAME_PROPRIETAIRE = 'app_point_proprietaire_index';



    
    
    #[Route('/locataire', name: 'app_point_locataire_index', methods: ['GET', 'POST'])]
    public function indexLocataire(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME_LOCATAIRE);

        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])
            ->add('nomPrenoms', TextColumn::class, ['field' => 'pro.nomPrenoms', 'label' => 'Proprietaire'])
            ->add('maisson', TextColumn::class, ['field' => 'mai.LibMaison', 'label' => 'Maison',])
            ->add('mois', TextColumn::class, ['label' => 'Mois'])
            ->add('MntFact', TextColumn::class, ['field' => 'en.denomination', 'label' => 'Total du'])
            ->add('SoldeFactLoc', TextColumn::class, ['label' => 'Montant'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Factureloc::class,
                'query' => function (QueryBuilder $qb) {
                    $qb->select('en,mai,c,f,a,pro')
                        ->from(Factureloc::class, 'f')
                        ->innerJoin('f.compagne', 'c')
                        ->innerJoin('c.entreprise', 'en')
                        ->join('f.appartement', 'a')
                        ->join('a.maisson', 'mai')
                        ->join('mai.proprio', 'pro');
                    //->andWhere('f.statut = :statut')
                    //->setParameter('statut', 'impayer')

                    if ($this->groupe != "SADM") {
                        $qb->andWhere('en = :entreprise')
                            ->setParameter('entreprise', $this->entreprise);
                    }
                }

            ])
            ->setName('dt_app_comptabilite_point_locataire');
        if ($permission != null) {

            $renders = [


                'show' => new ActionRender(function () use ($permission) {
                    if ($permission == 'R') {
                        return true;
                    } elseif ($permission == 'RD') {
                        return true;
                    } elseif ($permission == 'RU') {
                        return true;
                    } elseif ($permission == 'CRUD') {
                        return true;
                    } elseif ($permission == 'CRU') {
                        return true;
                    } elseif ($permission == 'CR') {
                        return true;
                    }
                    return true;
                }),

            ];


            $hasActions = false;

            foreach ($renders as $_ => $cb) {
                if ($cb->execute()) {
                    $hasActions = true;
                    break;
                }
            }

            if ($hasActions) {
                $table->add('id', TextColumn::class, [
                    'label' => 'Actions', 'orderable' => false, 'globalSearchable' => false, 'className' => 'grid_row_actions', 'render' => function ($value, Factureloc $context) use ($renders) {
                        $options = [
                            'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                            'target' => '#exampleModalSizeLg2',

                            'actions' => [

                                'show' => [
                                    'url' => $this->generateUrl('app_location_contratloc_show', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-eye', 'attrs' => ['class' => 'btn-primary'], 'render' => $renders['show']
                                ],
                            ]

                        ];
                        return $this->renderView('_includes/default_actions.html.twig', compact('options', 'context'));
                    }
                ]);
            }
        }

        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }


        return $this->render('comptabilite/point/locataire.html.twig', [
            'datatable' => $table,
            'permition' => $permission,

        ]);
    }

    /**
     * Fonction pour afficher la grip des payer
     *
     * @param  $request
     * @param DataTableFactory $dataTableFactory
     * @return Response
     */
    #[Route('/proprietaire', name: 'app_point_proprietaire_index', methods: ['GET', 'POST'])]
    public function indexProprietaire(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME_PROPRIETAIRE);

        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])

            ->add('locataire', TextColumn::class, ['field' => 'loc.NPrenoms', 'label' => 'Locataire'])
            ->add('maisson', TextColumn::class, ['field' => 'mai.LibMaison', 'label' => 'Maison',])
            ->add('mois', TextColumn::class, ['label' => 'Mois'])
            ->add('MntFact', TextColumn::class, ['field' => 'en.denomination', 'label' => 'Loyer'])
            ->add('SoldeFactLoc', TextColumn::class, ['label' => 'Montant'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Factureloc::class,
                'query' => function (QueryBuilder $qb) {
                    $qb->select('en,c,f,a,loc')
                        ->from(Factureloc::class, 'f')
                        ->innerJoin('f.compagne', 'c')
                        ->innerJoin('c.entreprise', 'en')
                        ->join('f.appartement', 'a')
                        ->join('a.maisson', 'mai')
                        ->join('f.locataire', 'loc');
                        //->andWhere('f.statut = :statut')
                        //->setParameter('statut', 'impayer')

                    if ($this->groupe != "SADM") {
                        $qb->andWhere('en = :entreprise')
                            ->setParameter('entreprise', $this->entreprise);
                    }
                }

            ])
            ->setName('dt_app_comptabilite_point_proprietaire');
        if ($permission != null) {

            $renders = [


                'show' => new ActionRender(function () use ($permission) {
                    if ($permission == 'R') {
                        return true;
                    } elseif ($permission == 'RD') {
                        return true;
                    } elseif ($permission == 'RU') {
                        return true;
                    } elseif ($permission == 'CRUD') {
                        return true;
                    } elseif ($permission == 'CRU') {
                        return true;
                    } elseif ($permission == 'CR') {
                        return true;
                    }
                    return true;
                }),

            ];


            $hasActions = false;

            foreach ($renders as $_ => $cb) {
                if ($cb->execute()) {
                    $hasActions = true;
                    break;
                }
            }

            if ($hasActions) {
                $table->add('id', TextColumn::class, [
                    'label' => 'Actions', 'orderable' => false, 'globalSearchable' => false, 'className' => 'grid_row_actions', 'render' => function ($value, Factureloc $context) use ($renders) {
                        $options = [
                            'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                            'target' => '#exampleModalSizeLg2',

                            'actions' => [

                                'show' => [
                                    'url' => $this->generateUrl('app_location_contratloc_show', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-eye', 'attrs' => ['class' => 'btn-primary'], 'render' => $renders['show']
                                ],
                            ]

                        ];
                        return $this->renderView('_includes/default_actions.html.twig', compact('options', 'context'));
                    }
                ]);
            }
        }

        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }


        return $this->render('comptabilite/point/proprietaire.html.twig', [
            'datatable' => $table,
            'permition' => $permission,

        ]);
    }
}
