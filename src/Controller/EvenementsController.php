<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
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
    public function index(): Response
    {
        $events = $this->repository->findToCome();
        return $this->render('evenements/index.html.twig', [
            'title' => 'Evènements à venir à Pagny la Blanche Côte',
            'titre' => 'Evénements à venir',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }

    /**
     * @Route("/actualite", name="actualite")
     */
    public function pastEvents(): Response
    {
        $events = $this->repository->findHasHappened();
        return $this->render('evenements/index-passe.html.twig', [
            'title' => 'Evènements passés à Pagny la Blanche Côte',
            'titre' => 'Evènements passés du village',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }

    /**
     * Finds and displays events which type is 0
     * 
     * @Route("/actuconseilmunicipal", name="actuconseilmunicipal")
     */
    public function actuConseilMunicipal(): Response
    {
        $events = $this->repository->findByType('0');
        return $this->render('evenements/index.html.twig', [
            'title' => 'Activites du Conseil Municipal de Pagny',
            'titre' => 'Activités du Conseil Municipal de Pagny',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }

    /**
     * @Route("/mariages", name="mariages")
     */
    public function mariages(): Response
    {
        $events = $this->repository->findByType('2');
        return $this->render('evenements/index-passe.html.twig', [
            'title' => 'Mariages à Pagny la Blanche Côte',
            'titre' => 'Mariages',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }

    /**
     * @Route("/deces", name="deces")
     */
    public function deces(): Response
    {
        $events = $this->repository->findByType('1');
        return $this->render('evenements/index-passe.html.twig', [
            'title' => 'Décés à Pagny la Blanche Cote',
            'titre' => 'Hommages',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }

    /**
     * @Route("/fetesactu", name="fetesactu")
     */
    public function fetesactu(): Response
    {
        $events = $this->repository->findByType('3');
        return $this->render('evenements/index-passe.html.twig', [
            'title' => 'Fêtes à Pagny la Blanche Côte',
            'titre' => 'Festivités à Pagny',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }

    /**
     * @Route("/fetesavenir", name="fetesavenir")
     */
    public function fetesavenir(): Response
    {
        $events = $this->repository->findByTypeToCome('3');
        return $this->render('evenements/index-avenir.html.twig', [
            'title' => 'Festivités annocées à Pagny la Blanche Côte',
            'titre' => 'Festivités annoncées',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }
    /**
     * @Route("/flashinfospasses", name="flashinfospasses")
     */
    public function flashinfospasses(): Response
    {
        $events = $this->repository->findByType('7');
        return $this->render('evenements/index-passe.html.twig', [
            'title' => 'FlashInfos passés de Pagny la Blanche Côte',
            'titre' => 'Flash Infos passés',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }
    /**
     * @Route("/flashinfos", name="flashinfos")
     */
    public function flashinfos(): Response
    {
        $events = $this->repository->findByTypeToCome('7');
        return $this->render('evenements/index-avenir.html.twig', [
            'title' => 'FlashInfos de Pagny la Blanche Côte',
            'titre' => 'Flash Infos',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }
    /**
     * @Route("/actualiteetevenements", name="actualiteetevenements")
     */
    public function bothEvents(): Response
    {
        $events = $this->repository->findHasHappenedAndToCome();
        return $this->render('evenements/index.html.twig', [
            'title' => 'Evènements à la Une de Pagny la Blanche Côte',
            'titre' => 'Actu\' à la Une',
            'current_menu' => 'evenements',
            'evenements' => $events
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
            return $this->redirectToRoute('evenement.show', [
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
