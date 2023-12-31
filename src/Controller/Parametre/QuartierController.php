<?php

namespace App\Controller\Parametre;

use App\Entity\Quartier;
use App\Form\QuartierType;
use App\Repository\QuartierRepository;
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
use Doctrine\ORM\QueryBuilder;

#[Route('/ads/parametre/quartier')]
class QuartierController extends BaseController
{
    const INDEX_ROOT_NAME = 'app_parametre_quartier_index';

    #[Route('/', name: 'app_parametre_quartier_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $table = $dataTableFactory->create()
            /* ->add('id', TextColumn::class, ['label' => 'Identifiant'])*/
            ->add('ville', TextColumn::class, ['label' => 'Ville', 'field' => 'v.lib_ville'])
            ->add('LibQuartier', TextColumn::class, ['label' => 'Libelle', 'field' => 'q.LibQuartier'])
            ->add('denomination', TextColumn::class, ['label' => 'Entreprise', 'field' => 'e.denomination'])

            ->createAdapter(ORMAdapter::class, [
                'entity' => Quartier::class,
                'query' => function (QueryBuilder $qb) {
                    $qb->select('v,q,e')
                        ->from(Quartier::class, 'q')
                        ->join('q.ville', 'v')
                        ->join('q.entreprise', 'e');

                    if ($this->groupe != "SADM") {
                        $qb->andWhere('q.entreprise = :entreprise')
                            ->setParameter('entreprise', $this->entreprise);
                    }
                }



            ])
            ->setName('dt_app_parametre_quartier');
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
                    'label' => 'Actions', 'orderable' => false, 'globalSearchable' => false, 'className' => 'grid_row_actions', 'render' => function ($value, Quartier $context) use ($renders) {
                        $options = [
                            'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                            'target' => '#exampleModalSizeLg2',

                            'actions' => [
                                'edit' => [
                                    'url' => $this->generateUrl('app_parametre_quartier_edit', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-pen', 'attrs' => ['class' => 'btn-default'], 'render' => $renders['edit']
                                ],
                                'show' => [
                                    'url' => $this->generateUrl('app_parametre_quartier_show', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-eye', 'attrs' => ['class' => 'btn-primary'], 'render' => $renders['show']
                                ],
                                'delete' => [
                                    'target' => '#exampleModalSizeNormal',
                                    'url' => $this->generateUrl('app_parametre_quartier_delete', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-trash', 'attrs' => ['class' => 'btn-main'], 'render' => $renders['delete']
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


        return $this->render('parametre/quartier/index.html.twig', [
            'datatable' => $table,
            'permition' => $permission
        ]);
    }

    #[Route('/new', name: 'app_parametre_quartier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuartierRepository $quartierRepository, FormError $formError): Response
    {
        $quartier = new Quartier();
        $form = $this->createForm(QuartierType::class, $quartier, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_parametre_quartier_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_parametre_quartier_index');


            if ($form->isValid()) {
                if($this->groupe != "SADM"){
                    $quartier->setEntreprise($this->entreprise);
                }
                $quartierRepository->save($quartier, true);
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

        return $this->renderForm('parametre/quartier/new.html.twig', [
            'quartier' => $quartier,
            'form' => $form,
            'user_groupe'=>$this->groupe
        ]);
    }

    #[Route('/{id}/show', name: 'app_parametre_quartier_show', methods: ['GET'])]
    public function show(Quartier $quartier): Response
    {
        return $this->render('parametre/quartier/show.html.twig', [
            'quartier' => $quartier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parametre_quartier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quartier $quartier, QuartierRepository $quartierRepository, FormError $formError): Response
    {

        $form = $this->createForm(QuartierType::class, $quartier, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_parametre_quartier_edit', [
                'id' => $quartier->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_parametre_quartier_index');


            if ($form->isValid()) {

                $quartierRepository->save($quartier, true);
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

        return $this->renderForm('parametre/quartier/edit.html.twig', [
            'quartier' => $quartier,
            'form' => $form,
            'user_groupe'=>$this->groupe
        ]);
    }

    #[Route('/{id}/delete', name: 'app_parametre_quartier_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Quartier $quartier, QuartierRepository $quartierRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'app_parametre_quartier_delete',
                    [
                        'id' => $quartier->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $quartierRepository->remove($quartier, true);

            $redirect = $this->generateUrl('app_parametre_quartier_index');

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

        return $this->renderForm('parametre/quartier/delete.html.twig', [
            'quartier' => $quartier,
            'form' => $form,
        ]);
    }
}
