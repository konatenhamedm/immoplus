<?php

namespace App\Controller\Configuration;

use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config/terrains')]
class  TerrainsConfigController extends BaseController
{
    private const MODULE_NAME = 'GESTION DE TERRAINS';
    private const INDEX_ROOT_NAME = 'app_config_terrains_index';

    #[Route(path: '/index', name: 'app_config_terrains_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb): Response
    {
        //dd('drtgjtyrter');
        $permission = $this->menu->getPermissionIfDifferentNull($this->security->getUser()->getGroupe()->getId(), self::INDEX_ROOT_NAME);

        $modules = [
            [
                'label' => ' SITES',
                'icon' => 'bi bi-list',
                'href' => $this->generateUrl('app_config_terrains_ls', ['module' => 'config'])
            ],


            [
                'label' => 'TERRAINS DISPONIBLES',
                'icon' => 'bi bi-list',
                'href' => $this->generateUrl('app_config_terrain_index', ['etat' => 'disponible'])
            ],
            [
                'label' => 'TERRAINS VENDUS',
                'icon' => 'bi bi-list',
                'href' => $this->generateUrl('app_config_terrain_index', ['etat' => 'vendu'])
            ],




        ];

        $breadcrumb->addItem([
            [
                'route' => 'app_default',
                'label' => 'Audiances'
            ],
            [
                'label' => 'Paramètres'
            ]
        ]);

        return $this->render('config/terrains/index.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb,
            'module_name' => self::MODULE_NAME,
            'permition' => $permission
        ]);
    }


    #[Route(path: '/{module}', name: 'app_config_terrains_ls', methods: ['GET', 'POST'])]
    public function liste(Request $request, string $module): Response
    {
        /**
         * @todo: A déplacer dans un service
         */
        $parametes = [

            'config' => [
                [
                    'label' => 'sites en attente approbation',
                    'id' => 'param_site',
                    'href' => $this->generateUrl('app_config_site_index', ['etat' => 'en_attente'])
                ],



                [
                    'label' => 'sites approuvés',
                    'id' => 'param_site',
                    'href' => $this->generateUrl('app_config_site_index', ['etat' => 'approuve'])
                ],
            ],
        ];


        return $this->render('config/stock/liste.html.twig', ['links' => $parametes[$module] ?? []]);
    }
}
