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
            'title' => 'Demarches', 'titre' => 'Démarches administratives',  'current_menu' => 'mairie'
        ]);
    }

    /**
     * @Route("/urbanisme", name="urbanisme")
     */
    public function urbanisme()
    {
        return $this->render('mairie/indexurbanisme.html.twig', [
            'title' => 'Urbanisme', 'titre' => 'Urbanisme', 'current_menu' => 'mairie'
        ]);
    }

    /**
     * @Route("/cadastre", name="cadastre")
     */
    public function cadastre()
    {
        return $this->render('mairie/indexcadastre.html.twig', [
            'title' => 'Cadastre', 'titre' => 'Plan cadastral interactif', 'current_menu' => 'mairie'
        ]);
    }
    /**
     * @Route("/conseilmunicipal", name="conseilmunicipal")
     */
    public function conseilmunicipal()
    {
        return $this->render('mairie/indexconseil.html.twig', [
            'title' => 'ConseilMunicipal', 'titre' => 'Conseil Municipal et Commissions',  'current_menu' => 'mairie'
        ]);
    }

    /**
     * @Route("/associations", name="associations")
     */
    public function associations()
    {
        return $this->render('mairie/indexassociations.html.twig', [
            'title' => 'Associations', 'titre' => 'Associations de la commune', 'current_menu' => 'mairie'
        ]);
    }
}
