<?php

namespace App\Controller\Terrains;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Workflow\Exception\LogicException;


#[Route('/ads/terrains/site')]
class SiteController extends BaseController
{
    const INDEX_ROOT_NAME = 'app_config_site_index';

    #[Route('/{etat}/liste', name: 'app_config_site_index', methods: ['GET', 'POST'])]
    public function index(Request $request, string $etat, SiteRepository $siteRepository, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);
        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Site::class,
                'query' => function (QueryBuilder $req) use ($etat) {
                    $req->select(['s'])
                        ->from(Site::class, 's')
                    ;
                    if ($etat == 'en_attente') {
                        $req->andWhere("s.etat =:etat")
                            ->setParameter('etat', 'en_attente');
                    } elseif ($etat == 'approuve') {
                        $req->andWhere("s.etat =:etat")
                            ->setParameter('etat', 'approuve');
                    }
                }
            ])
            ->setName('dt_app_config_site_' . $etat);
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
                    'render' => function ($value, Site $context) use ($renders) {
                        $options = [
                            'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                            'target' => '#exampleModalSizeLg2',

                            'actions' => [
                                'workflow_validation' => [
                                    'url' => $this->generateUrl('app_terrains_site_workflow', ['id' => $value]),
                                    'ajax' => true,
                                    'icon' => '%icon%  bi bi-arrow-repeat',
                                    'attrs' => ['class' => 'btn-danger'],
                                    'render' => $renders['workflow_validation']
                                ],
                                'edit' => [
                                    'url' => $this->generateUrl('app_terrains_site_edit', ['id' => $value]),
                                    'ajax' => true,
                                    'icon' => '%icon% bi bi-pen',
                                    'attrs' => ['class' => 'btn-default'],
                                    'render' => $renders['edit']
                                ],
                                'show' => [
                                    'url' => $this->generateUrl('app_terrains_site_show', ['id' => $value]),
                                    'ajax' => true,
                                    'icon' => '%icon% bi bi-eye',
                                    'attrs' => ['class' => 'btn-primary'],
                                    'render' => $renders['show']
                                ],
                                'delete' => [
                                    'target' => '#exampleModalSizeNormal',
                                    'url' => $this->generateUrl('app_terrains_site_delete', ['id' => $value]),
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


        return $this->render('terrains/site/index.html.twig', [
            'datatable' => $table,
            'permition' => $permission,
            'etat' => $etat,
        ]);
    }

    #[Route('/new', name: 'app_terrains_site_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SiteRepository $siteRepository, FormError $formError): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site, [
            'method' => 'POST',
            'etat' => 'create',
            'action' => $this->generateUrl('app_terrains_site_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_config_terrains_index');


            if ($form->isValid()) {

                $siteRepository->save($site, true);
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

        return $this->renderForm('terrains/site/new.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_terrains_site_show', methods: ['GET'])]
    public function show(Site $site): Response
    {
        return $this->render('terrains/site/show.html.twig', [
            'site' => $site,
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

    #[Route('/{id}/edit', name: 'app_terrains_site_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Site $site, SiteRepository $siteRepository, FormError $formError): Response
    {

        $form = $this->createForm(SiteType::class, $site, [
            'method' => 'POST',
            'etat' => 'create',
            'action' => $this->generateUrl('app_terrains_site_edit', [
                'id' => $site->getId()
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

                $siteRepository->save($site, true);
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

        return $this->renderForm('terrains/site/edit.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_terrains_site_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Site $site, SiteRepository $siteRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'app_terrains_site_delete',
                    [
                        'id' => $site->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $siteRepository->remove($site, true);

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

        return $this->renderForm('terrains/site/delete.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/workflow/validation', name: 'app_terrains_site_workflow', methods: ['GET', 'POST'])]
    public function workflow(Request $request, Site $site, SiteRepository $siteRepository, EntityManagerInterface $entityManager, FormError $formError): Response
    {
        $etat =  $site->getEtat();
        // $site = $siteRepository->find(1);
        // dd($site); // Remplacez par un ID existant
        $validationGroups = ['Default', 'FileRequired', 'oui'];
        $filePath = 'site';
        $form = $this->createForm(SiteType::class, $site, [
            'method' => 'POST',
            'etat' => $etat,
            'action' => $this->generateUrl('app_terrains_site_workflow', [
                'id' =>  $site->getId()
            ])
        ]);


        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $response = [];
            $redirect = $this->generateUrl('app_config_terrains_index');
            $workflow = $this->workflow->get($site, 'site');

            if ($form->isValid()) {
                if ($form->getClickedButton()->getName() === 'approuver') {

                    try {
                        if ($workflow->can($site, 'approuver')) {
                            $workflow->apply($site, 'approuver');

                            $this->em->flush();
                        }
                    } catch (LogicException $e) {

                        $this->addFlash('danger', sprintf('No, that did not work: %s', $e->getMessage()));
                    }
                    $siteRepository->save($site, true);
                } else {
                    $siteRepository->save($site, true);
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

        return $this->renderForm('terrains/site/workflow.html.twig', [
            'site' => $site,
            'form' => $form,
            'id' => $site->getId(),

            'etat' => json_encode($etat)
        ]);
    }
}
