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
}
