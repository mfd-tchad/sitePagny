<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MairieController extends AbstractController
{
    /**
     * @Route("/mairie", name="mairie")
     */
    public function index()
    {
        return $this->render('mairie/index.html.twig', [
            'title' => 'Mairie', 'titre' => 'Mairie de Pagny',  'current_menu' => 'mairie'
        ]);
    }

    /**
     * @Route("/demarches", name="demarches")
     */
    public function demarches()
    {
        return $this->render('mairie/indexdemarches.html.twig', [
            'title' => 'Demarches', 'titre' => 'Démarches administratives',  'current_menu' => 'demarches'
        ]);
    }

    /**
     * @Route("/conseilmunicipal", name="conseilmunicipal")
     */
    public function conseilmunicipal()
    {
        return $this->render('mairie/indexconseil.html.twig', [
            'title' => 'ConseilMunicipal', 'titre' => 'Mairie de Pagny',  'current_menu' => 'mairie'
        ]);
    }
}
