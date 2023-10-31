<?php


namespace App\Controller;


use App\Controller\FileTrait;
use App\Service\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class BaseController extends AbstractController
{
    use FileTrait;

    protected const UPLOAD_PATH = 'media_entreprise';
    protected $em;
    protected $security;
    protected $menu;
    protected  $entreprise;
    protected  $groupe;


    public function __construct(EntityManagerInterface $em,Menu $menu,Security $security)
    {
        $this->em = $em;
        $this->security = $security;
        $this->menu = $menu;
        $this->entreprise = $this->security->getUser()->getGroupe()->getEntreprise();
        $this->groupe = $this->security->getUser()->getGroupe()->getName();
    }

   
}