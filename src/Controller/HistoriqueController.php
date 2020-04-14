<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueController extends AbstractController
{
    /**
     * @Route("/historique", name="historique")
     */
    public function index()
    {
        return $this->render('historique/index.html.twig', [
            'historique' => 'Historique du village', 'current-menu' => 'historique'
        ]);
    }
}
