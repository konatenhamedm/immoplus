<?php

namespace App\Controller\Comptabilite;

use App\Entity\CompteCltT;
use App\Form\CompteCltTType;
use App\Repository\CompteCltTRepository;
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
use Doctrine\ORM\EntityManagerInterface;

#[Route('/ads/comptabilite/compte/clt/t')]
class CompteCltTController extends BaseController
{
    const INDEX_ROOT_NAME = 'app_comptabilite_compte_clt_t_index';

    #[Route('/', name: 'app_comptabilite_compte_clt_t_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {


        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $table = $dataTableFactory->create()
            // ->add('id', TextColumn::class, ['label' => 'Identifiant'])
            ->add('site', TextColumn::class, ['label' => 'Site', 'field' => 's.nom'])
            ->add('terrain', TextColumn::class, ['label' => 'Terain', 'field' => 't.num'])
            ->add('datecreation', DateTimeColumn::class,  ['label' => 'Date de creation ', 'format' => 'd/m/Y', 'searchable' => false])
            ->add('montant', TextColumn::class,  ['label' => 'Montant dû '])
            ->add('montantpaye', TextColumn::class, ['label' => 'Total payé', "searchable" => false, 'render' => function ($value, CompteCltT $context) {
                $montantpaye = (float)$context->getMontant() - (float)$context->getSolde();
                return $montantpaye;
            }])
            ->add('solde', TextColumn::class,  ['label' => 'Solde '])
            ->createAdapter(ORMAdapter::class, [
                'entity' => CompteCltT::class,
                'query' => function (QueryBuilder $qb) {
                    $qb->select(['c,t,s'])
                        ->from(CompteCltT::class, 'c')
                        ->join('c.terrain', 't')
                        ->join('t.site', 's')
                        ->andWhere("t.etat =:etat")
                        ->setParameter('etat', "vendu")
                        ->orderBy('c.id ', 'DESC');
                }
            ])
            ->setName('dt_app_comptabilite_compte_clt_t');
        if ($permission != null) {

            $renders = [

                'payer_load' => new ActionRender(function () use ($permission) {
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
                    'render' => function ($value, CompteCltT $context) use ($renders) {
                        $options = [
                            'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                            'target' => '#exampleModalSizeLg2',

                            'actions' => [
                                // 'edit' => [
                                //     'url' => $this->generateUrl('app_comptabilite_compte_clt_t_edit', ['id' => $value]),
                                //     'ajax' => true,
                                //     'icon' => '%icon% bi bi-pen',
                                //     'attrs' => ['class' => 'btn-default'],
                                //     'render' => $renders['edit']
                                // ],
                                // 'show' => [
                                //     'url' => $this->generateUrl('app_comptabilite_compte_clt_t_show', ['id' => $value]),
                                //     'ajax' => true,
                                //     'icon' => '%icon% bi bi-eye',
                                //     'attrs' => ['class' => 'btn-primary'],
                                //     'render' => $renders['show']
                                // ],
                                'payer_load' => [
                                    'target' => '#exampleModalSizeSm2',
                                    'url' => $this->generateUrl('app_config_frais_paiement_index', ['id' => $value]),
                                    'ajax' => true,
                                    'stacked' => false,
                                    'icon' => '%icon% bi bi-cash',
                                    'attrs' => ['class' => 'btn-warning'],
                                    'render' => $renders['payer_load']
                                ],
                                // 'delete' => [
                                //     'target' => '#exampleModalSizeNormal',
                                //     'url' => $this->generateUrl('app_comptabilite_compte_clt_t_delete', ['id' => $value]),
                                //     'ajax' => true,
                                //     'icon' => '%icon% bi bi-trash',
                                //     'attrs' => ['class' => 'btn-main'],
                                //     'render' => $renders['delete']
                                // ]
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


        return $this->render('comptabilite/compte_clt_t/index.html.twig', [
            'datatable' => $table,
            'permition' => $permission
        ]);
    }



    #[Route('/new', name: 'app_comptabilite_compte_clt_t_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FormError $formError): Response
    {
        $compteCltT = new CompteCltT();
        $form = $this->createForm(CompteCltTType::class, $compteCltT, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_comptabilite_compte_clt_t_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_comptabilite_compte_clt_t_index');



            if ($form->isValid()) {


                $entityManager->persist($compteCltT);
                $entityManager->flush();

                $data = true;
                $message = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = 500;
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

        return $this->renderForm('comptabilite/compte_clt_t/new.html.twig', [
            'compte_clt_t' => $compteCltT,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_comptabilite_compte_clt_t_show', methods: ['GET'])]
    public function show(CompteCltT $compteCltT): Response
    {
        return $this->render('comptabilite/compte_clt_t/show.html.twig', [
            'compte_clt_t' => $compteCltT,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comptabilite_compte_clt_t_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CompteCltT $compteCltT, EntityManagerInterface $entityManager, FormError $formError): Response
    {

        $form = $this->createForm(CompteCltTType::class, $compteCltT, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_comptabilite_compte_clt_t_edit', [
                'id' => $compteCltT->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_comptabilite_compte_clt_t_index');


            if ($form->isValid()) {

                $entityManager->persist($compteCltT);
                $entityManager->flush();

                $data = true;
                $message = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = 500;
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

        return $this->renderForm('comptabilite/compte_clt_t/edit.html.twig', [
            'compte_clt_t' => $compteCltT,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_comptabilite_compte_clt_t_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, CompteCltT $compteCltT, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'app_comptabilite_compte_clt_t_delete',
                    [
                        'id' => $compteCltT->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $entityManager->remove($compteCltT);
            $entityManager->flush();

            $redirect = $this->generateUrl('app_comptabilite_compte_clt_t_index');

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

        return $this->renderForm('comptabilite/compte_clt_t/delete.html.twig', [
            'compte_clt_t' => $compteCltT,
            'form' => $form,
        ]);
    }
}
