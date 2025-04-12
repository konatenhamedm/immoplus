<?php


namespace App\Controller\Configuration;

use App\Service\Breadcrumb;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('admin/config/parametre/frais/paiement')]
class ConfigPaiementController extends AbstractController
{

    const INDEX_ROOT_NAME = 'app_config_frais_paiement_index';

    #[Route(path: '/frais/compte/{id}', name: 'app_config_frais_paiement_index', methods: ['GET', 'POST'])]
    // #[RoleMethod(title: 'Gestion des Paramètres', as: 'index')]
    public function indexConfigVersementFrais(Request $request, Breadcrumb $breadcrumb,int $id): Response
    {   
        $module = $request->query->get('module');

       
        $modules = [
            [
                'label' => 'DETAIL DES VERSEMENTS',
                'icon' => 'bi bi-list',
                'module' => 'gestion',
                'href' => $this->generateUrl('app_comptabilte_ligneversementfrais_index', ['idR' => $id])
                //'href' => $this->generateUrl('app_inscription_liste_versements', ['id' => $id])
            ],
            [

                'label' => 'DETAIL DU COMPTE',
                'icon' => 'bi bi-list',
                'module' => 'general',
                'href' => $this->generateUrl('app_comptabilite_compte_clt_t_show', ['id' => $id])
                // 'href' => $this->generateUrl('app_inscription_inscription_paiement_ok', ['id' => $id])
            ],

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


        if ($module) {
            $modules = array_filter($modules, fn($_module) => $_module['module'] == $module);
        }

        return $this->render('config/paiement/index_frais.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb,
          
            'id' => $id
        ]);
    }
}
