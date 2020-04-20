<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'titre' => 'Bienvenue sur le site de Pagny la Blanche Côte', 'current_menu' => 'home'
        ]);
    }

     /**
     * @Route("/historique", name="historique")
     */
    public function historique()
    {
        return $this->render('historique/index.html.twig', [
            'title' => 'Historique', 'titre' => 'Historique du village', 'current_menu' => 'historique'
        ]);
    }
    
    /**
     * @Route("/viepratique", name="viepratique")
     */
    public function viepratique()
    {
        return $this->render('viepratique/index.html.twig', [
            'title' => 'ViePratique', 'titre' => 'Vie pratique', 'current_menu' => 'viepratique'
        ]);
    }

    /**
     * @Route("/cadredevie", name="cadredevie")
     */
    public function cadredevie()
    {
        return $this->render('cadredevie/index.html.twig', [
            'title' => 'CadreDeVie', 'titre' => 'Cadre de vie', 'current_menu' => 'cadredevie'
        ]);
    }
    /**
     * @Route("/reserve", name="reserve")
     */
    public function reserve()
    {
        return $this->render('reserve/index.html.twig', [
            'title' => 'Reserve', 'titre' => 'Reserve naturelle régionale', 'current_menu' => 'patrimoine'
        ]);
    }

    /**
     * @Route("/patrimoine", name="patrimoine")
     */
    public function patrimoine()
    {
        return $this->render('patrimoine/index.html.twig', [
            'title' => 'Patrimoine', 'titre' => 'Patrimoine', 'current_menu' => 'patrimoine'
        ]);
    }
}
