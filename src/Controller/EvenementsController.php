<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use \DateTime;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;


class EvenementsController extends AbstractController
{
    private $repository;

    public function __construct(EvenementRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @Route("/evenements", name="evenements")
     */
    public function index() : Response
    {
        $events = $this->repository->findToCome();
        return $this->render('evenements/index.html.twig', [
            'title' => 'Evenements', 'titre' => 'Evénements à venir',  'current_menu' => 'evenements', 'evenements' => $events
        ]);
    }

    /**
     * @Route("/actualite", name="actualite")
     */
    public function pastEvents() : Response
    {
        $events = $this->repository->findHasHappened();
        return $this->render('evenements/index.html.twig', [
            'title' => 'Actualite', 'titre' => 'Actualité du village',  'current_menu' => 'actualite', 'evenements' => $events
        ]);
    }

    /**
     * @Route("evenements/{slug}-{id}", name="evenement.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Evenement $evenement
     * @return Response
     */
    public function show(Evenement $evenement, string $slug): Response
    {
        if ($evenement->getSlug() !== $slug) {
            return $this-redirecToRoute('evenement.show', [
                'id' => $evenement->getId(),
                'slug' => $evenement->getSlug()
            ], 301);
        }
        return $this->render('evenement/show.html.twig', [
            'title' => 'Actualite', 'titre' => 'Actualité du village',
            'evenement' => $evenement,
            'current-menu' => 'evenements'
        ]);
    }
    
}
