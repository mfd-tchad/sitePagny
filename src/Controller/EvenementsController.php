<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Evenement;
use Psr\Log\LoggerInterface;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * EvenementsController class
 */
class EvenementsController extends AbstractController
{
    private $repository;
    private $logger;

    public function __construct(EvenementRepository $repository, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }
    
    /**
     * @Route("/evenements", name="evenements")
     */
    public function index(): Response
    {
        try {
            $events = $this->repository->findToCome();
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from event table with findToCome function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux événements est survenu. 
                Veuillez réessayer ultérieurement.");
        }
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
        try {
            $events = $this->repository->findHasHappened();
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from event table with findHasHappened function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                Veuillez réessayer ultérieurement.");
        }
        return $this->render('evenements/index.html.twig', [
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
        try {
            $events = $this->repository->findByType('0');
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from evenement table with findByType function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                Veuillez réessayer ultérieurement.");
        }
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
        try {
            $events = $this->repository->findByType('2');
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from evenement table with findByType function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                Veuillez réessayer ultérieurement.");
        }
        return $this->render('evenements/index.html.twig', [
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
        try {
            $events = $this->repository->findByType('1');
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from evenement table with findByType function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                Veuillez réessayer ultérieurement.");
        }
        return $this->render('evenements/index.html.twig', [
            'title' => 'Décés à Pagny la Blanche Cote',
            'titre' => 'Hommages',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }

    /**
     * @Route("/actufetes", name="actufetes")
     */
    public function fetesactu(): Response
    {
        try {
            $events = $this->repository->findByType('3');
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from evenement table with findByType function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                Veuillez réessayer ultérieurement.");
        }
        return $this->render('evenements/index.html.twig', [
            'title' => 'Fêtes à Pagny la Blanche Côte',
            'titre' => 'Festivités à Pagny',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }

    /**
     * @Route("/flashinfos", name="flashinfos")
     */
    public function flashinfos(): Response
    {
        try {
            $events = $this->repository->findByType('7');
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from evenement table with findByType function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                Veuillez réessayer ultérieurement.");
        }
        return $this->render('evenements/index.html.twig', [
            'title' => 'FlashInfos de Pagny la Blanche Côte',
            'titre' => 'Flash Infos',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }
    /**
     * @Route("/flashinfosavenir", name="flashinfosavenir")
     */
    public function flashinfosavenir(): Response
    {
        try {
            $events = $this->repository->findByTypeToCome('7');
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from evenement table with findByType function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                Veuillez réessayer ultérieurement.");
        }
        return $this->render('evenements/index.html.twig', [
            'title' => 'FlashInfos de Pagny la Blanche Côte',
            'titre' => 'Flash Infos - Evenements à venir',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }
    /**
     * @Route("/alaune", name="alaune")
     */
    public function bothEvents(): Response
    {
        try {
            $events = $this->repository->findHasHappenedAndToCome();
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from evenement table with findHasHappenedAndToCome function",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                Veuillez réessayer ultérieurement.");
        }
        return $this->render('evenements/index.html.twig', [
            'title' => 'Evènements à la Une de Pagny la Blanche Côte',
            'titre' => 'Actu\' à la Une',
            'current_menu' => 'evenements',
            'evenements' => $events
        ]);
    }
    /**
     * @Route("evenements/{slug}-{id}", name="evenement.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(string $slug, int $id): Response
    {
        /* if ($evenement->getSlug() !== $slug) {
            return $this->redirectToRoute('evenement.show', [
                'id' => $evenement->getId(),
                'slug' => $evenement->getSlug()
            ], 301);
        } */
        try {
            $evenement = $this->repository->findOneById($id);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from evenenement table with findOneById($id)",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
              Veuillez réessayer ultérieurement.");
        }
        return $this->render('evenement/show.html.twig', [
            'title' => 'Actualite', 'titre' => 'Actualité du village',
            'evenement' => $evenement,
            'current-menu' => 'evenements'
        ]);
    }
}
