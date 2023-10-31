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
    //const INDEX_ROOT_NAME_MAISON = 'app_config_location_m_index';
    //const INDEX_ROOT_NAME_CONTRAT = 'app_config_location_contrats_index';




    #[Route(path: '/', name: 'app_config_location_contrats_index', methods: ['GET', 'POST'])]
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

        return $this->render('config/location/index.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb,
            'permition' => $permission
        ]);
    }  
}
