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
            'title' => 'Historique', 'titre' => 'Historique du village', 'current_menu' => 'patrimoins'
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
     * @Route("/jeunesse", name="jeunesse")
     */
    public function jeunesse()
    {
        return $this->render('jeunesse/index.html.twig', [
            'title' => 'Jeunesse', 'titre' => 'Jeunesse', 'current_menu' => 'jeunesse'
        ]);
    }

    /**
     * @Route("/ileauxenfants", name="ileauxenfants")
     */
    public function ileauxenfants()
    {
        return $this->render('jeunesse/indexileauxenfants.html.twig', [
            'title' => 'IleAuxEnfants', 'titre' => 'île aux Enfants', 'current_menu' => 'jeunesse'
        ]);
    }

    /**
     * @Route("/sportsetloisirs", name="sportsetloisirs")
     */
    public function sportsetloisirs()
    {
        return $this->render('jeunesse/indexsportsetloisirs.html.twig', [
            'title' => 'SportEtLoisirs', 'titre' => 'Sports et Loisirs', 'current_menu' => 'jeunesse'
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
