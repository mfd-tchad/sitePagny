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
            'title' => 'Pagny la Blanche Cote - site Officiel','titre' => 'Bienvenue sur le site de Pagny la Blanche Côte', 'current_menu' => 'home'
        ]);
    }

    /**
     * @Route("/viepratique", name="viepratique")
     */
    public function viepratique()
    {
        return $this->render('viepratique/index.html.twig', [
            'title' => 'Vivre à Pagny la Blanche Cote', 'titre' => 'Vie pratique', 'current_menu' => 'viepratique'
        ]);
    }
    /**
     * @Route("/scolaire", name="scolaire")
     */
    public function scolaire()
    {
        return $this->render('viepratique/indexscolaire.html.twig', [
            'title' => 'Bus scolaires et écoles près de Pagny la Blanche Cote', 'titre' => 'Bus scolaires et Ecoles', 'current_menu' => 'viepratique'
        ]);
    }

    /**
     * @Route("/serviceaines", name="serviceaines")
     */
    public function serviceaines()
    {
        return $this->render('viepratique/indexserviceaines.html.twig', [
            'title' => 'Services aux ainés à Pagny la Blanche Cote', 'titre' => 'Services aux Ainés', 'current_menu' => 'viepratique'
        ]);
    }

    /**
     * @Route("/ordures", name="ordures")
     */
    public function ordures()
    {
        return $this->render('viepratique/indexordures.html.twig', [
            'title' => 'Gestion des déchets à Pagny la Blanche Côte', 'titre' => 'Gestion des déchets', 'current_menu' => 'viepratique'
        ]);
    }
    /**
     * @Route("/animationetloisirs", name="animationetloisirs")
     */
    public function animationetloisirs()
    {
        return $this->render('animationetloisirs/index.html.twig', [
            'title' => 'Animation et Loisirs à Pagny la Blanche Côte', 'titre' => 'Animation et Loisirs', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/jeunesse", name="jeunesse")
     */
    public function jeunesse()
    {
        return $this->render('jeunesse/index.html.twig', [
            'title' => 'Activités Jeunesse à Pagny la Blanche Côte', 'titre' => 'Jeunesse', 'current_menu' => 'jeunesse'
        ]);
    }

    /**
     * @Route("/aines", name="aines")
     */
    public function aines()
    {
        return $this->render('animationetloisirs/indexaines.html.twig', [
            'title' => 'Animations pour les ainés à Pagny la Blanche Côte', 'titre' => 'Animations pour les Ainés', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/videgreniers", name="videgreniers")
     */
    public function videgreniers()
    {
        return $this->render('animationetloisirs/indexvidegreniers.html.twig', [
            'title' => 'Vide-Greniers à Pagny la Blanche Côte', 'titre' => 'Vide-Greniers', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/concerts", name="concerts")
     */
    public function concerts()
    {
        return $this->render('animationetloisirs/indexconcerts.html.twig', [
            'title' => 'Concerts à Pagny la Blanche Côte', 'titre' => 'Concerts', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/fetes", name="fetes")
     */
    public function fetes()
    {
        return $this->render('animationetloisirs/indexfetes.html.twig', [
            'title' => 'Fêtes à Pagny la Blanche Côte', 'titre' => 'Fêtes', 'current_menu' => 'animationetloisirs'
        ]);
    }
    
     /**
     * @Route("/sportsetloisirs", name="sportsetloisirs")
     */
    public function sportsetloisirs()
    {
        return $this->render('animationetloisirs/indexsportsetloisirs.html.twig', [
            'title' => 'Sport et Loisirs à Pagny la Blanche Côte', 'titre' => 'Sports et Loisirs', 'current_menu' => 'animationetloisirs'
        ]);
    }

    /**
     * @Route("/ileauxenfants", name="ileauxenfants")
     */
     public function ileauxenfants()
    {
        return $this->render('jeunesse/indexileauxenfants.html.twig', [
            'title' => 'Association pour les enfants de Pagny la Blanche Cote', 'titre' => 'Association L\'île aux Enfants', 'current_menu' => 'associations'
        ]);
    }

    /**
     * @Route("/historique", name="historique")
     */
    public function historique()
    {
        return $this->render('historique/index.html.twig', [
            'title' => 'Historique de Pagny la Blanche Cote', 'titre' => 'Historique du village', 'current_menu' => 'patrimoins'
        ]);
    }
    
    /**
     * @Route("/saintgregoire", name="saintgregoire")
     */
    public function saintgregoire()
    {
        return $this->render('patrimoine/indexassocstgregoire.html.twig', [
            'title' => 'Association Saint Gregoire le Grand - Pagny la Blanche Cote', 'titre' => 'Association Saint Grégoire le Grand', 'current_menu' => 'associations'
        ]);
    }
    
    /**
     * @Route("/eglise", name="eglise")
     */
    public function eglise()
    {
        return $this->render('patrimoine/indexeglise.html.twig', [
            'title' => 'Eglise Saint Grégoire le Grand de Pagny la Blanche Côte', 'titre' => 'Eglise Saint Grégoire le Grand', 'current_menu' => 'patrimoine'
        ]);
    }
    /**
     * @Route("/reserve", name="reserve")
     */
    public function reserve()
    {
        return $this->render('reserve/index.html.twig', [
            'title' => 'Réserve Naturelle Régionale de Pagny la Blanche Côte', 'titre' => 'Reserve naturelle régionale', 'current_menu' => 'patrimoine'
        ]);
    }

    /**
     * @Route("/patrimoine", name="patrimoine")
     */
    public function patrimoine()
    {
        return $this->render('patrimoine/index.html.twig', [
            'title' => 'Patrimoine de Pagny la Blanche Cote', 'titre' => 'Patrimoine', 'current_menu' => 'patrimoine'
        ]);
    }

    
}
