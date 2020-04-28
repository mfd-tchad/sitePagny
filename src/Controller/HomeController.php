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
     * @Route("/scolaire", name="scolaire")
     */
    public function scolaire()
    {
        return $this->render('viepratique/indexscolaire.html.twig', [
            'title' => 'Scolaire', 'titre' => 'Bus scolaires et Ecoles', 'current_menu' => 'viepratique'
        ]);
    }

    /**
     * @Route("/serviceaines", name="serviceaines")
     */
    public function serviceaines()
    {
        return $this->render('viepratique/indexserviceaines.html.twig', [
            'title' => 'ServiceAines', 'titre' => 'Services aux Ainés', 'current_menu' => 'viepratique'
        ]);
    }

    /**
     * @Route("/ordures", name="ordures")
     */
    public function ordures()
    {
        return $this->render('viepratique/indexordures.html.twig', [
            'title' => 'GestionDechets', 'titre' => 'Gestion des déchets', 'current_menu' => 'viepratique'
        ]);
    }
    /**
     * @Route("/animationetloisirs", name="animationetloisirs")
     */
    public function animationetloisirs()
    {
        return $this->render('animationetloisirs/index.html.twig', [
            'title' => 'AnimationetLoisirs', 'titre' => 'Animation et Loisirs', 'current_menu' => 'animationetloisirs'
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
     * @Route("/aines", name="aines")
     */
    public function aines()
    {
        return $this->render('animationetloisirs/indexaines.html.twig', [
            'title' => 'Aines', 'titre' => 'Animations pour les Ainés', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/videgreniers", name="videgreniers")
     */
    public function videgreniers()
    {
        return $this->render('animationetloisirs/indexvidegreniers.html.twig', [
            'title' => 'Vide-Greniers', 'titre' => 'Vide-Greniers', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/concerts", name="concerts")
     */
    public function concerts()
    {
        return $this->render('animationetloisirs/indexconcerts.html.twig', [
            'title' => 'Concerts', 'titre' => 'Concerts', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/fetes", name="fetes")
     */
    public function fetes()
    {
        return $this->render('animationetloisirs/indexfetes.html.twig', [
            'title' => 'Fetes', 'titre' => 'Fêtes', 'current_menu' => 'animationetloisirs'
        ]);
    }
    
     /**
     * @Route("/sportsetloisirs", name="sportsetloisirs")
     */
    public function sportsetloisirs()
    {
        return $this->render('animationetloisirs/indexsportsetloisirs.html.twig', [
            'title' => 'SportEtLoisirs', 'titre' => 'Sports et Loisirs', 'current_menu' => 'jeunesse'
        ]);
    }

    /**
     * @Route("/ileauxenfants", name="ileauxenfants")
     */
     public function ileauxenfants()
    {
        return $this->render('jeunesse/indexileauxenfants.html.twig', [
            'title' => 'IleAuxEnfants', 'titre' => 'île aux Enfants', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/saintgregoire", name="saintgregoire")
     */
    public function saintgregoire()
    {
        return $this->render('patrimoine/index.html.twig', [
            'title' => 'SaintGregoire', 'titre' => 'Association Saint Grégoire', 'current_menu' => 'patrimoine'
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
