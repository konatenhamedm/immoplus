<?php

namespace App\Controller\Terrains;

use App\Entity\Terrain;
use App\Form\TerrainType;
use App\Repository\TerrainRepository;
use App\Service\ActionRender;
use App\Service\FormError;
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
use App\Entity\CompteCltT;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Asset\Exception\LogicException;

#[Route('/ads/terrains/terrain')]
class TerrainController extends BaseController
{
    const INDEX_ROOT_NAME = 'app_config_terrain_index';

    #[Route('/{etat}/liste', name: 'app_config_terrain_index', methods: ['GET', 'POST'])]
    public function index(Request $request, string $etat, TerrainRepository $terrainRepository, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Terrain::class,
                'query' => function (QueryBuilder $req) use ($etat) {
                    $req->select(['t'])
                        ->from(Terrain::class, 't')
                    ;

                    if ($etat == 'disponible') {
                        $req->andWhere("t.etat =:etat")
                            ->setParameter('etat', "disponible");
                    } elseif ($etat == 'vendu') {
                        $req->andWhere("t.etat =:etat")
                            ->setParameter('etat', "vendu");
                    }
                }
            ])
            ->setName('dt_app_config_terrain_' . $etat);
        if ($permission != null) {

            $renders = [
                'workflow_validation' =>  new ActionRender(function () use ($permission) {
                    if ($permission == 'R') {
                        return false;
                    } elseif ($permission == 'RD') {
                        return false;
                    } elseif ($permission == 'RU') {
                        return true;
                    } elseif ($permission == 'RUD') {
                        return true;
                    } elseif ($permission == 'CRU') {
                        return true;
                    } elseif ($permission == 'CR') {
                        return false;
                    } else {
                        return true;
                    }
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
                    'label' => 'Actions',
                    'orderable' => false,
                    'globalSearchable' => false,
                    'className' => 'grid_row_actions',
                    'render' => function ($value, Terrain $context) use ($renders) {
                        $options = [
                            'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                            'target' => '#exampleModalSizeLg2',

                            'actions' => [
                                'workflow_validation' => [
                                    'url' => $this->generateUrl('app_terrains_terrain_workflow', ['id' => $value]),
                                    'ajax' => true,
                                    'icon' => '%icon%  bi bi-arrow-repeat',
                                    'attrs' => ['class' => 'btn-danger'],
                                    'render' => $renders['workflow_validation']
                                ],
                                'edit' => [
                                    'url' => $this->generateUrl('app_terrains_terrain_edit', ['id' => $value]),
                                    'ajax' => true,
                                    'icon' => '%icon% bi bi-pen',
                                    'attrs' => ['class' => 'btn-default'],
                                    'render' => $renders['edit']
                                ],
                                'show' => [
                                    'url' => $this->generateUrl('app_terrains_terrain_show', ['id' => $value]),
                                    'ajax' => true,
                                    'icon' => '%icon% bi bi-eye',
                                    'attrs' => ['class' => 'btn-primary'],
                                    'render' => $renders['show']
                                ],
                                'delete' => [
                                    'target' => '#exampleModalSizeNormal',
                                    'url' => $this->generateUrl('app_terrains_terrain_delete', ['id' => $value]),
                                    'ajax' => true,
                                    'icon' => '%icon% bi bi-trash',
                                    'attrs' => ['class' => 'btn-main'],
                                    'render' => $renders['delete']
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


        return $this->render('terrains/terrain/index.html.twig', [
            'datatable' => $table,
            'permition' => $permission,
            'etat' => $etat,
        ]);
    }

    #[Route('/new', name: 'app_terrains_terrain_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        TerrainRepository $terrainRepository,
        EntityManagerInterface $em,
        FormError $formError
    ): Response {
        $terrain = new Terrain();
        $form = $this->createForm(TerrainType::class, $terrain, [
            'method' => 'POST',
            'etat' => 'create',
            'action' => $this->generateUrl('app_terrains_terrain_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_config_terrains_index');

            $montantTotal = $form->get('prix');
            if (!$montantTotal && $montantTotal >= 0) {
                $prixTerrain = str_replace(' ', '', $montantTotal);
                $montantTerrain = is_numeric($prixTerrain) ? (float)$prixTerrain : 0; // Validation et conversion


            }

            if ($form->isValid()) {

                $terrain = $form->getData();

                // On récupère le prix depuis le terrain
                $prixTerrain = str_replace(' ', '', $terrain->getPrix());
                $montant = is_numeric($prixTerrain) ? $prixTerrain : "0";

                $compteClt = new CompteCltT();
                $compteClt->setTerrain($terrain)
                    ->setMontant($montant) // <- Important : une vraie chaîne ici
                    ->setSolde($montant);  // <- Pareil ici (si solde est aussi une string)

                $terrain->addCompteCltT($compteClt);

                $terrainRepository->save($terrain, true);
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

        return $this->renderForm('terrains/terrain/new.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_terrains_terrain_show', methods: ['GET'])]
    public function show(Terrain $terrain): Response
    {
        return $this->render('terrains/terrain/show.html.twig', [
            'terrain' => $terrain,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_terrains_terrain_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Terrain $terrain, EntityManagerInterface $em, TerrainRepository $terrainRepository, FormError $formError): Response
    {

        $form = $this->createForm(TerrainType::class, $terrain, [
            'method' => 'POST',
            'etat' => 'create',
            'action' => $this->generateUrl('app_terrains_terrain_edit', [
                'id' => $terrain->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_config_terrains_index');


            if ($form->isValid()) {

                $terrainRepository->save($terrain, true);
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

        return $this->renderForm('terrains/terrain/edit.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_terrains_terrain_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Terrain $terrain, TerrainRepository $terrainRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'app_terrains_terrain_delete',
                    [
                        'id' => $terrain->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $terrainRepository->remove($terrain, true);

            $redirect = $this->generateUrl('app_config_terrains_index');

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

        return $this->renderForm('terrains/terrain/delete.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/gestion/audience/tableau', name: 'app_gestion_audience_justification', methods: ['GET', 'POST'])]
    // public function justification(Request $request, Audience $audience, AudienceRepository $audienceRepository, FormError $formError): Response
    // {
    //     $form = $this->createForm(JutificationAudienceType::class, $audience, [
    //         'method' => 'POST',
    //         'type' => 'create',
    //         'etat' => 'create',
    //         'action' => $this->generateUrl('app_gestion_audience_justification', [
    //             'id' =>  $audience->getId()
    //         ])
    //     ]);

    //     $data = null;
    //     $statutCode = Response::HTTP_OK;

    //     $isAjax = $request->isXmlHttpRequest();


    //     $form->handleRequest($request);

    //     if ($form->isSubmitted()) {
    //         $response = [];
    //         $redirect = $this->generateUrl('app_config_audience_index');


    //         if ($form->isValid()) {

    //             $audience->setEtat('audience_rejeter');
    //             $audienceRepository->save($audience, true);
    //             $data = true;
    //             $message       = 'Opération effectuée avec succès';
    //             $statut = 1;
    //             $this->addFlash('success', $message);
    //         } else {
    //             $message = $formError->all($form);
    //             $statut = 0;
    //             $statutCode = Response::HTTP_INTERNAL_SERVER_ERROR;
    //             if (!$isAjax) {
    //                 $this->addFlash('warning', $message);
    //             }
    //         }


    //         if ($isAjax) {
    //             return $this->json(compact('statut', 'message', 'redirect', 'data'), $statutCode);
    //         } else {
    //             if ($statut == 1) {
    //                 return $this->redirect($redirect, Response::HTTP_OK);
    //             }
    //         }
    //     }

    //     return $this->renderForm('gestion/audience/jutification.html.twig', [
    //         'audience' => $audience,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}/workflow/validation', name: 'app_terrains_terrain_workflow', methods: ['GET', 'POST'])]
    public function workflow(Request $request, Terrain $terrain, TerrainRepository $terrainRepository, EntityManagerInterface $entityManager, FormError $formError): Response
    {
        $etat =  $terrain->getEtat();

        $filePath = 'terrain';
        $form = $this->createForm(TerrainType::class, $terrain, [
            'method' => 'POST',
            'etat' => $etat,
            'action' => $this->generateUrl('app_terrains_terrain_workflow', [
                'id' =>  $terrain->getId()
            ])
        ]);


        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $response = [];
            $redirect = $this->generateUrl('app_config_terrains_index');
            $workflow = $this->workflow->get($terrain, 'terrain');

            if ($form->isValid()) {
                if ($form->getClickedButton()->getName() === 'vendre') {

                    try {
                        if ($workflow->can($terrain, 'vendu')) {
                            $workflow->apply($terrain, 'vendu');

                            $this->em->flush();
                        }
                    } catch (LogicException $e) {

                        $this->addFlash('danger', sprintf('No, that did not work: %s', $e->getMessage()));
                    }
                    $terrainRepository->save($terrain, true);
                } else {
                    $terrainRepository->save($terrain, true);
                }

                // if ($form->getClickedButton()->getName() === 'rejeter') {
                //     try {
                //         if ($workflow->can($audience, 'rejeter')) {
                //             $workflow->apply($audience, 'rejeter');
                //             $this->em->flush();
                //         }
                //     } catch (LogicException $e) {

                //         $this->addFlash('danger', sprintf('No, that did not work: %s', $e->getMessage()));
                //     }
                //     $audienceRepository->save($audience, true);
                // } else {
                //     $audienceRepository->save($audience, true);
                // }

                $data = true;
                $message       = 'Opération effectuée avec succès';
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

        return $this->renderForm('terrains/terrain/workflow.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
            'id' => $terrain->getId(),

            'etat' => json_encode($etat)
        ]);
    }
}
