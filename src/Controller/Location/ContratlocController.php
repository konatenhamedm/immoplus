<?php

namespace App\Controller\Location;

use App\Entity\Appartement;
use App\Entity\Contratloc;
use App\Entity\Locataire;
use App\Form\ContratlocType;
use App\Repository\AppartementRepository;
use App\Repository\ContratlocRepository;
use App\Service\ActionRender;
use App\Service\FormError;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

#[Route('/ads/location/contratloc')]
class ContratlocController extends BaseController
{
    const INDEX_ROOT_NAME = 'app_location_contratloc_index';

    #[Route('/', name: 'app_location_contratloc_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])
            ->add('entreprise', TextColumn::class, ['field' => 'en.denomination', 'label' => 'Entreprise'])
            ->add('locataire', TextColumn::class, ['field' => 'a.libAppart', 'label' => 'Locataire'])
            ->add('appartement', TextColumn::class, ['label' => 'Appartement','field' => 'a.LibAppart'])
            ->add('MntLoyer', TextColumn::class, ['label' => 'Mnt Loyer'])
            ->add('MntCaution', TextColumn::class, ['label' => 'Mnt Caution'])
            ->add('MntAvance', TextColumn::class, ['label' => 'Mnt Avancé'])
            ->add('TotVerse', TextColumn::class, ['label' => 'Total Versé','field' => 'l.TotVerse'])

            ->createAdapter(ORMAdapter::class, [
                'entity' => Contratloc::class,
                'query' => function (QueryBuilder $qb)  {
                    $qb->select('en, l,a')
                        ->from(Contratloc::class, 'l')
                        ->join('l.entreprise', 'en')
                        ->join('l.appart', 'a')
                        ->andWhere('l.Etat = :etat')
                        ->setParameter('etat',1)
                    ;

                    if ($this->groupe != "SADM") {
                        $qb->andWhere('en = :entreprise')
                            ->setParameter('entreprise', $this->entreprise);
                    }
                }

            ])
            ->setName('dt_app_location_contratloc');
        if ($permission != null) {

            $renders = [

                'resilier' => new ActionRender(function () use ($permission) {
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
                    return true;
                }),
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
                    return true;
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
                    return true;
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

            if ($hasActions ) {
                $table->add('id', TextColumn::class, [
                    'label' => 'Actions', 'orderable' => false, 'globalSearchable' => false, 'className' => 'grid_row_actions', 'render' => function ($value, Contratloc $context) use ($renders) {
                        $options = [
                            'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                            'target' => '#exampleModalSizeLg2',

                            'actions' => [
                                'resilier' => [
                                    'target' => '#exampleModalSizeNormal',
                                    'url' => $this->generateUrl('app_location_contratloc_resilier', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-lock', 'attrs' => ['class' => 'btn-danger'], 'render' => $renders['resilier']
                                ],
                                'edit' => [
                                    'target' => '#exampleModalSizeSm2',
                                    'url' => $this->generateUrl('app_location_contratloc_edit', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-pen', 'attrs' => ['class' => 'btn-default'], 'render' => $renders['edit']
                                ],
                                'show' => [
                                    'url' => $this->generateUrl('app_location_contratloc_show', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-eye', 'attrs' => ['class' => 'btn-primary'], 'render' => $renders['show']
                                ],
                                'delete' => [
                                    'target' => '#exampleModalSizeNormal',
                                    'url' => $this->generateUrl('app_location_contratloc_delete', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-trash', 'attrs' => ['class' => 'btn-main'], 'render' => $renders['delete']
                                ]
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


        return $this->render('location/contratloc/index.html.twig', [
            'datatable' => $table,
            'permition' => $permission,

        ]);
    }

    #[Route('/resilier', name: 'app_location_contratloc_resilier_index', methods: ['GET', 'POST'])]
    public function indexResilier(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])
            ->add('entreprise', TextColumn::class, ['field' => 'en.denomination', 'label' => 'Entreprise'])
            ->add('locataire', TextColumn::class, ['field' => 'a.libAppart', 'label' => 'Locataire'])
            ->add('appartement', TextColumn::class, ['label' => 'Appartement','field' => 'a.LibAppart'])
            ->add('MntLoyer', TextColumn::class, ['label' => 'Mnt Loyer'])
            ->add('MntCaution', TextColumn::class, ['label' => 'Mnt Caution'])
            ->add('MntAvance', TextColumn::class, ['label' => 'Mnt Avancé'])
            ->add('TotVerse', TextColumn::class, ['label' => 'Total Versé','field' => 'l.TotVerse'])

            ->createAdapter(ORMAdapter::class, [
                'entity' => Contratloc::class,
                'query' => function (QueryBuilder $qb)  {
                    $qb->select('en, l,a')
                        ->from(Contratloc::class, 'l')
                        ->join('l.entreprise', 'en')
                        ->join('l.appart', 'a')
                        ->andWhere('l.Etat = :etat')
                        ->setParameter('etat',0)
                    ;

                    if ($this->groupe != "SADM") {
                        $qb->andWhere('en = :entreprise')
                            ->setParameter('entreprise', $this->entreprise);
                    }
                }

            ])
            ->setName('dt_app_location_contratloc_resilier');
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

            if ($hasActions ) {
                $table->add('id', TextColumn::class, [
                    'label' => 'Actions', 'orderable' => false, 'globalSearchable' => false, 'className' => 'grid_row_actions', 'render' => function ($value, Contratloc $context) use ($renders) {
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


        return $this->render('location/contratloc/index_resilier.html.twig', [
            'datatable' => $table,
            'permition' => $permission,

        ]);
    }

    #[Route('/new', name: 'app_location_contratloc_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContratlocRepository $contratlocRepository,AppartementRepository $appartementRepository, FormError $formError): Response
    { 
        $validationGroups = ['Default', 'FileRequired', 'autre'];
        $contratloc = new Contratloc();
        $form = $this->createForm(ContratlocType::class, $contratloc, [
            'method' => 'POST',
            'doc_options' => [
                'uploadDir' => $this->getUploadDir(self::UPLOAD_PATH, true),
                'attrs' => ['class' => 'filestyle'],
            ],
            'validation_groups' => $validationGroups,
            'action' => $this->generateUrl('app_location_contratloc_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
          $appart = $appartementRepository->find($form->getData()->getAppart()->getId());
          $somme = $form->getData()->getMntCaution() + $form->getData()->getFraisanex() + $form->getData()->getMntAvance();
            $response = [];
            $redirect = $this->generateUrl('app_location_contratloc_index');


            if ($form->isValid()) {
                if($appart){
                    $appart->setOqp(1);
                    $appartementRepository->save($appart,true);
                }
                $contratloc->setMntLoyer($appart->getLoyer());
                $contratloc->setTotVerse($somme);
                $contratloc->setEtat(1);
                $contratlocRepository->save($contratloc, true);
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

        return $this->renderForm('location/contratloc/new.html.twig', [
            'contratloc' => $contratloc,
            'form' => $form,
            'user_groupe' => $this->groupe
        ]);
    }

    #[Route('/{id}/show', name: 'app_location_contratloc_show', methods: ['GET'])]
    public function show(Contratloc $contratloc): Response
    {
        return $this->render('location/contratloc/show.html.twig', [
            'contratloc' => $contratloc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_location_contratloc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contratloc $contratloc, ContratlocRepository $contratlocRepository, FormError $formError): Response
    {
        $validationGroups = ['Default', 'FileRequired', 'autre'];
        $form = $this->createForm(ContratlocType::class, $contratloc, [
            'method' => 'POST',
            'doc_options' => [
                'uploadDir' => $this->getUploadDir(self::UPLOAD_PATH, true),
                'attrs' => ['class' => 'filestyle'],
            ],
            'validation_groups' => $validationGroups,
            'action' => $this->generateUrl('app_location_contratloc_edit', [
                'id' => $contratloc->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $somme = $form->getData()->getMntCaution() + $form->getData()->getFraisanex() + $form->getData()->getMntAvance();
            $response = [];
            $redirect = $this->generateUrl('app_config_location_index');


            if ($form->isValid()) {
                $contratloc->setTotVerse($somme);
                $contratlocRepository->save($contratloc, true);
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

        return $this->renderForm('location/contratloc/edit.html.twig', [
            'contratloc' => $contratloc,
            'form' => $form,
            'user_groupe' => $this->groupe
        ]);
    }


    #[Route('/{id}/resilier', name: 'app_location_contratloc_resilier', methods: ['GET', 'POST'])]
    public function resilier(Request $request, Contratloc $contratloc, ContratlocRepository $contratlocRepository, FormError $formError): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'app_location_contratloc_resilier',
                    [
                        'id' => $contratloc->getId()
                    ]
                )
            )
            ->setMethod('POST')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $contratloc->setEtat(0);
            $contratlocRepository->save($contratloc, true);

            $redirect = $this->generateUrl('app_config_location_index');

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

        return $this->renderForm('location/contratloc/resilier.html.twig', [
            'contratloc' => $contratloc,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/delete', name: 'app_location_contratloc_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Contratloc $contratloc, ContratlocRepository $contratlocRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'app_location_contratloc_delete',
                    [
                        'id' => $contratloc->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $contratlocRepository->remove($contratloc, true);

            $redirect = $this->generateUrl('app_location_contratloc_index');

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

        return $this->renderForm('location/contratloc/delete.html.twig', [
            'contratloc' => $contratloc,
            'form' => $form,
        ]);
    }
}
