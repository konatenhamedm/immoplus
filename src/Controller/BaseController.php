<?php


namespace App\Controller;


use App\Controller\FileTrait;
use App\Service\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Config\Security\PasswordHasherConfig;

class BaseController extends AbstractController
{
    use FileTrait;

    protected const UPLOAD_PATH = 'media_entreprise';
    protected $em;
    protected $security;
    protected $menu;
    protected  $entreprise;
    protected  $groupe;
    protected  $hasher;


    public function __construct(EntityManagerInterface $em,Menu $menu,Security $security,UserPasswordHasherInterface $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
        $this->security = $security;
        $this->menu = $menu;
        $this->entreprise = $this->security->getUser()->getEmploye()->getEntreprise();
        $this->groupe = $this->security->getUser()->getGroupe()->getCode();
    }

   
}