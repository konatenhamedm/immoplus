<?php

namespace App\Controller\Comptabilite;

use App\Entity\Campagne;
use App\Entity\Factureloc;
use App\Entity\Maison;
use App\Form\CampagneType;
use App\Repository\AppartementRepository;
use App\Repository\CampagneRepository;
use App\Repository\FacturelocRepository;
use App\Repository\JoursMoisEntrepriseRepository;use App\Service\ActionRender;
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

#[Route('/ads/comptabilite/campagne')]
class CampagneController extends BaseController
{
    const INDEX_ROOT_NAME = 'app_comptabilite_campagne_index';
  
    #[Route('/', name: 'app_comptabilite_campagne_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $table = $dataTableFactory->create()
            ->add('LibCampagne', TextColumn::class, ['label' => 'Campagne'])
            ->add('NbreProprio', NumberColumn::class, ['label' => 'Nbre Proprio'])
            ->add('NbreLocataire', NumberColumn::class, ['label' => 'Nbre locataire'])
            ->add('MntTotal', NumberColumn::class, ['label' => 'Total'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Campagne::class,
                'query' => function (QueryBuilder $qb) {
                    $qb->select('m,e')
                        ->from(Campagne::class, 'm')
                        ->join('m.entreprise', 'e');


                    if ($this->groupe != "SADM") {
                        $qb->andWhere('e = :entreprise')
                            ->setParameter('entreprise', $this->entreprise);
                    }
                }
            ])
            ->setName('dt_app_comptabilite_campagne');
        if ($permission != null) {

            $renders = [
                'edit' => new ActionRender(function () use ($permission) {
                    if ($permission == 'R') {
                        return false;
                    } elseif ($permission == 'RD') {
                        return false;
                    } elseif ($permission == 'RU') {
                        return true;
                    } elseif ($permission == 'CRUD') {
                        return true;
                    } elseif ($permission == 'CRU') {
                        return true;
                    } elseif ($permission == 'CR') {
                        return false;
                    }
                }),
                'delete' => new ActionRender(function () use ($permission) {
                    if ($permission == 'R') {
                        return false;
                    } elseif ($permission == 'RD') {
                        return true;
                    } elseif ($permission == 'RU') {
                        return false;
                    } elseif ($permission == 'CRUD') {
                        return true;
                    } elseif ($permission == 'CRU') {
                        return false;
                    } elseif ($permission == 'CR') {
                        return false;
                    }
                }),
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
                    'label' => 'Actions', 'orderable' => false, 'globalSearchable' => false, 'className' => 'grid_row_actions', 'render' => function ($value, Campagne $context) use ($renders) {
                        $options = [
                            'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                            'target' => '#exampleModalSizeLg2',

                            'actions' => [
                                /* 'edit' => [
                                    'url' => $this->generateUrl('app_comptabilite_campagne_edit', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-pen', 'attrs' => ['class' => 'btn-default'], 'render' => $renders['edit']
                                ],*/
                                'show' => [
                                    'url' => $this->generateUrl('app_comptabilite_campagne_show', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-eye', 'attrs' => ['class' => 'btn-primary'], 'render' => $renders['show']
                                ],
                                /* 'delete' => [
                                    'target' => '#exampleModalSizeNormal',
                                    'url' => $this->generateUrl('app_comptabilite_campagne_delete', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-trash', 'attrs' => ['class' => 'btn-main'], 'render' => $renders['delete']
                                ]*/
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


        return $this->render('comptabilite/campagne/index.html.twig', [
            'datatable' => $table,
            'permition' => $permission
        ]);
    }
    
    
    #[Route('/impayer', name: 'app_gestion_loyer_impayer_index', methods: ['GET', 'POST'])]
    public function indeximpayer(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])
            ->add('MntFact', TextColumn::class, ['field' => 'en.denomination', 'label' => 'Loyer'])
            ->add('locataire', TextColumn::class, ['field' => 'loc.NPrenoms', 'label' => 'Locataire'])
            ->add('appartement', TextColumn::class, ['field' => 'a.LibAppart', 'label' => 'Appartement',])
            ->add('SoldeFactLoc', TextColumn::class, ['label' => 'Montant'])
            ->add('DateLimite', DateTimeColumn::class, ['label' => 'Date limite'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Factureloc::class,
                'query' => function (QueryBuilder $qb) {
                    $qb->select('en,c,f,a,loc')
                        ->from(Factureloc::class, 'f')
                    ->innerJoin('f.compagne', 'c')
                    ->innerJoin('c.entreprise', 'en')
                     ->join('f.appartement', 'a')
                        ->join('f.locataire', 'loc')
                        ->andWhere('f.statut = :statut')
                        ->setParameter('statut', 'impayer');

                   if ($this->groupe != "SADM") {
                        $qb->andWhere('en = :entreprise')
                            ->setParameter('entreprise', $this->entreprise);
                    }
                }

            ])
            ->setName('dt_app_comptabilite_loyer_impayer');
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


        return $this->render('comptabilite/campagne/impayer.html.twig', [
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
    #[Route('/payer', name: 'app_gestion_loyer_payer_index', methods: ['GET', 'POST'])]
    public function indexpayer(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])
            ->add('MntFact', TextColumn::class, ['field' => 'en.denomination', 'label' => 'Loyer'])
            ->add('locataire', TextColumn::class, ['field' => 'loc.NPrenoms', 'label' => 'Locataire'])
            //->add('appartement', TextColumn::class, ['field' => 'a.LibAppart', 'label' => 'Appartement',])
            ->add('SoldeFactLoc', TextColumn::class, ['label' => 'Montant'])
            ->add('DateLimite', DateTimeColumn::class, ['label' => 'Date limite',''])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Factureloc::class,
                'query' => function (QueryBuilder $qb) {
                    $qb->select('en,c,f,loc')
                        ->from(Factureloc::class, 'f')
                        ->innerJoin('f.compagne', 'c')
                        ->innerJoin('c.entreprise', 'en')
                        ->join('f.appartement', 'a')
                        ->join('f.locataire', 'loc')
                        ->andWhere('f.statut = :statut')
                        ->setParameter('statut', 'impayer');

                       if ($this->groupe != "SADM") {
                        $qb->andWhere('en = :entreprise')
                            ->setParameter('entreprise', $this->entreprise);
                    }
                }

            ])
            ->setName('dt_app_comptabilite_loyer_payer');
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


        return $this->render('comptabilite/campagne/payer.html.twig', [
            'datatable' => $table,
            'permition' => $permission,

        ]);
    }

  
    /**
     * @throws NonUniqueResultException
     */
    #[Route('/new', name: 'app_comptabilite_campagne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CampagneRepository $campagneRepository,JoursMoisEntrepriseRepository $joursMoisEntrepriseRepository, FormError $formError, ContratlocRepository $contratlocRepository, AppartementRepository $appartementRepository, FacturelocRepository $facturelocRepository): Response
    {
        $campagne = new Campagne();

        $somme = 0;
        $dateActuelle = new \DateTime();
        $dateMoisSuivant = $dateActuelle->add(new \DateInterval('P1M'));
        $dateMoisSuivant->setDate($dateMoisSuivant->format('Y'), $dateMoisSuivant->format('m'), $joursMoisEntrepriseRepository->getJour($this->entreprise));

        foreach ($contratlocRepository->getContratLocActif($this->entreprise) as $contratloc) {
            $campagneContrat = new CampagneContrat();
            $campagneContrat->setLoyer($contratloc->getAppart()->getLoyer());
            $campagneContrat->setProprietaire($contratloc->getAppart()->getMaisson()->getProprio()->getNomPrenoms());
            $campagneContrat->setMaison($contratloc->getAppart()->getMaisson()->getLibMaison());
            $campagneContrat->setNumAppartement($contratloc->getAppart()->getLibAppart());
            $campagneContrat->setLocataire($contratloc->getLocataire()->getNprenoms());
            $campagneContrat->setDateLimite($dateMoisSuivant);
            $campagne->AddCampagneContrat($campagneContrat);

            $somme += $contratloc->getAppart()->getLoyer();
        }
        $campagne->setMntTotal($somme);
        $form = $this->createForm(CampagneType::class, $campagne, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_comptabilite_campagne_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_comptabilite_campagne_index');


            if ($form->isValid()) {

                if ($form->get('campagneContrats')->getData()) {
                    $solde = 0;

                    foreach ($form->get('campagneContrats')->getData() as $data) {
                        $facture = new Factureloc();
                        $appart = $appartementRepository->getAppart($data->getNumAppartement(), $data->getMaison());
                        $contrat = $contratlocRepository->findOneBy(array('appart' => $appart));

                        if ($contrat->getMntAvance() > 0) {
                            $solde = $data->getLoyer() - $contrat->getMntAvance();

                            if ($contrat->getMntAvance() >= $data->getLoyer()) {
                                $facture->setStatut('payer');
                            } else {
                                $facture->setStatut('impayer');
                            }
                        } else {
                            $solde = 0;
                            $facture->setStatut('impayer');
                        }

                        // $form->getData()->getMntAvance()

                        $facture->setLocataire($contrat->getLocataire());
                        $facture->setAppartement($appart);
                        $facture->setLibFacture($form->get('LibCampagne')->getData());
                        $facture->setCompagne($campagne);
                        $facture->setMntFact($data->getLoyer());
                        $facture->setContrat($contrat);
                        $facture->setDateLimite($data->getDateLimite());
                        $facture->setDateEmission(new \DateTime());
                        $facture->setMois($form->get('mois')->getData());
                        $facture->setSoldeFactLoc($solde);


                        $facturelocRepository->save($facture, true);
                    }
                }
                $campagne->setNbreProprio(1);
                $campagne->setEntreprise($this->entreprise);
                $campagne->setNbreLocataire(1);
                $campagneRepository->save($campagne, true);


                $data = true;
                $message = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                if (!$isAjax) {
                    $this->addFlash('warning', $message);
                }
            }


            if ($isAjax) {
                return $this->json(compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }
        }

        return $this->renderForm('comptabilite/campagne/new.html.twig', [
            'campagne' => $campagne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_comptabilite_campagne_show', methods: ['GET'])]
    public function show(Campagne $campagne): Response
    {
        return $this->render('comptabilite/campagne/show.html.twig', [
            'campagne' => $campagne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comptabilite_campagne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Campagne $campagne, CampagneRepository $campagneRepository, FormError $formError): Response
    {

        $form = $this->createForm(CampagneType::class, $campagne, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_comptabilite_campagne_edit', [
                'id' => $campagne->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_comptabilite_campagne_index');


            if ($form->isValid()) {

                $campagneRepository->save($campagne, true);
                $data = true;
                $message = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                if (!$isAjax) {
                    $this->addFlash('warning', $message);
                }
            }


            if ($isAjax) {
                return $this->json(compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }
        }

        return $this->renderForm('comptabilite/campagne/edit.html.twig', [
            'campagne' => $campagne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_comptabilite_campagne_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Campagne $campagne, CampagneRepository $campagneRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'app_comptabilite_campagne_delete',
                    [
                        'id' => $campagne->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $campagneRepository->remove($campagne, true);

            $redirect = $this->generateUrl('app_comptabilite_campagne_index');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut' => 1,
                'message' => $message,
                'redirect' => $redirect,
                'data' => $data
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return $this->json($response);
            }
        }

        return $this->renderForm('comptabilite/campagne/delete.html.twig', [
            'campagne' => $campagne,
            'form' => $form,
        ]);
    }
}
