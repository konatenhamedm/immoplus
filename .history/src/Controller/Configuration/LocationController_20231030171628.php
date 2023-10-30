<?php

namespace App\Controller\Configuration;

use App\Controller\BaseController;
use App\Repository\CiviliteRepository;
use App\Service\Breadcrumb;
use App\Service\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/admin/config/location')]
class LocationController extends BaseController
{

    const INDEX_ROOT_NAME = 'app_config_location_index';
    const INDEX_ROOT_NAME_MAISON = 'app_config_location_m_index';
    const INDEX_ROOT_NAME_CONTRAT = 'app_config_location_contrats_index';


    /* private $menu;
     public function __construct(Menu $menu){
         $this->menu = $menu;
     }*/

    #[Route(path: '/', name: 'app_config_location_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb): Response
    {

        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        /* if($this->menu->getPermission()){
             $redirect = $this->generateUrl('app_default');
             return $this->redirect($redirect);
             //dd($this->menu->getPermission());
         }*/
        $modules = [
            [
                'label' => 'Loc',
                'icon' => 'bi bi-list',
                'href' => $this->generateUrl('app_config_location_ls', ['module' => 'locataire'])
            ],
            [
                'label' => 'Ressource humaine',
                'icon' => 'bi bi-truck',
                'href' => $this->generateUrl('app_config_location_ls', ['module' => 'contratloc'])
            ],
          /*  [
                'label' => 'Gestion utilisateur',
                'icon' => 'bi bi-users',
                'href' => $this->generateUrl('app_config_location_ls', ['module' => 'utilisateur'])
            ],*/


        ];

        $breadcrumb->addItem([
            [
                'route' => 'app_default',
                'label' => 'Tableau de bord'
            ],
            [
                'label' => 'Paramètres'
            ]
        ]);

        return $this->render('config/location/index.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb,
            'permition' => $permission
        ]);
    }

   

    #[Route(path: '/contrats', name: 'app_config_location_contrats_index', methods: ['GET', 'POST'])]
    public function indexContrat(Request $request, Breadcrumb $breadcrumb): Response
    {

        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME_CONTRAT);

        /* if($this->menu->getPermission()){
             $redirect = $this->generateUrl('app_default');
             return $this->redirect($redirect);
             //dd($this->menu->getPermission());
         }*/
        $modules = [
            [
                'label' => 'Années',
                'icon' => 'bi bi-list',
                'href' => $this->generateUrl('app_location_annee_index')
            ],
            [
                'label' => 'Motifs résiliation',
                'icon' => 'bi bi-truck',
                'href' => $this->generateUrl('app_location_motif_index')
            ]



        ];

        $breadcrumb->addItem([
            [
                'route' => 'app_default',
                'label' => 'Tableau de bord'
            ],
            [
                'label' => 'Contrats'
            ]
        ]);

        return $this->render('config/location/contrat/index.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb,
            'permition' => $permission
        ]);
    }



    #[Route(path: '/{module}', name: 'app_config_location_ls', methods: ['GET', 'POST'])]
    public function liste(Request $request, string $module): Response
    {
        /**
         * @todo: A déplacer dans un service
         */
        $locations = [

            'locataire' => [

                [
                    'label' => 'Groupes modules',
                    'id' => 'param_groupe_m',
                    'href' => $this->generateUrl('app_utilisateur_groupe_module_index')
                ],
                [
                    'label' => 'Module',
                    'id' => 'param_module',
                    'href' => $this->generateUrl('app_utilisateur_module_index')
                ],
                [
                    'label' => 'Permissions',
                    'id' => 'param_permission',
                    'href' => $this->generateUrl('app_utilisateur_permition_index')
                ],
                [
                    'label' => 'Les elements du menu',
                    'id' => 'param_permission_groupe',
                    'href' => $this->generateUrl('app_utilisateur_module_groupe_permition_index')
                ]

            ],
            'contratloc' => [
                [
                    'label' => 'Fonction',
                    'id' => 'param_categorie',
                    'href' => $this->generateUrl('app_location_fonction_index')
                ],
                [
                    'label' => 'Direction',
                    'id' => 'param_direction',
                    'href' => $this->generateUrl('app_location_service_index')
                ],

                [
                    'label' => 'Employé',
                    'id' => 'param_client',
                    'href' => $this->generateUrl('app_utilisateur_employe_index')
                ],
                /*  [
                    'label' => 'Fournisseur',
                    'id' => 'param_fournisseur',
                    'href' => $this->generateUrl('app_rh_fournisseur_index')
                ],*/


            ],

           /* 'config' => [
                [
                    'label' => 'Civilité',
                    'id' => 'param_article',
                    'href' => $this->generateUrl('app_location_civilite_index')
                ],
                [
                    'label' => 'Icons',
                    'id' => 'param_cm',
                    'href' => $this->generateUrl('app_location_icon_index')
                ],
                [
                    'label' => 'Configuration application',
                    'id' => 'param_p',
                    'href' => $this->generateUrl('app_location_config_app_index')
                ]


            ],*/


        ];


        return $this->render('config/location/liste.html.twig', ['links' => $locations[$module] ?? []]);
    }
}
