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
}
