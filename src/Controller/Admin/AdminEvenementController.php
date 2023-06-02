<?php

namespace App\Controller\Admin;

use Exception;
use DateTime;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Psr\Log\LoggerInterface;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Controller for Event admin
 * 
 * @category Class
 * @author Marie-Françoise Dewulf <marie-francoise@mfdewulf.fr>
 * 
 */
class AdminEvenementController extends AbstractController
{
    /**
     * @var EvenementRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param EvenementRepository $repository for retrieving events from storage
     * @param EntityManagerInterface $em used for flushing new/updated event
     * @param LoggerInterface $logger used to log errors
     */
    public function __construct(EvenementRepository $repository, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Retrieves all events from storage into an array and sends it to display
     * 
     * @Route("/admin/evenement", name="admin.evenement.index")
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration sur ce site.");
            return $this->redirectToRoute('evenements');
        }
        try {
            $evenements = $this->repository->findAllHasHappenedAndToCome();
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from event table with findAllHasHappenedAndToCome()",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
              Veuillez réessayer ultérieurement.");
        }
        
        return $this->render('admin/evenement/index.html.twig', [
            'title' => 'Admin',
            'titre' => 'Administration des événements',
            'current_menu' => 'admin',
            'evenements' => $evenements
            ]
        );
    }

    /**
     * Creates a new event from a form completed by user and stores it 
     * 
     * @Route("/admin/evenement/new", name="admin.evenement.new")
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration sur ce site.");
            return $this->redirectToRoute('evenements');
        }
        $evenement = new Evenement();
        $evenement->setDescription("Bonjour,");
        $form = $this->createForm(EvenementType::class, $evenement);

        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve data from form with handleRequest for new event",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu et l'évènement n'a pas pu être créé. 
              Veuillez réessayer sans charger une image.");
            return $this->redirectToRoute('admin.evenement.index');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // création d'un nouvel evenement dans le tableau de cache de Symfony
                $evenement->setCreatedAt(new Datetime());
                $evenement->setUpdatedAt(new Datetime());
                $this->em->persist($evenement);
                $this->em->flush(); // mise à jour de la base
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to persist or flush a new event in evenement table)",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème est survenu et l'évènement n'a pas pu être créé. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('admin.evenement.index');
            }
            $this->addFlash('success', "Evenement créé avec succés");
            // On redirige l'utilisateur vers la liste des événements
            return $this->redirectToRoute('admin.evenement.index');
        }
        return $this->render('admin/evenement/new.html.twig', [
            'title' => 'Creation',
            'titre' => 'Création d\'un événement',
            'current_menu' => 'admin',
            'evenement' => $evenement,
            'form' => $form->createView()
            ]
        );
    }

    /**
     * Displays an event as a form the user can update and then stores the updated event into storage
     * 
     * @Route("/admin/evenement/{id}", name="admin.evenement.edit", methods="GET|POST")
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(int $id, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration sur ce site.");
            return $this->redirectToRoute('evenements');
        }
        try {
            $evenement = $this->repository->findOneById($id);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from event table with findOneById($id)",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
              Veuillez réessayer ultérieurement.");
            return $this->redirectToRoute('admin.evenement.index');
        }
        try {
            $form = $this->createForm(EvenementType::class, $evenement);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to create form of EvenementType class for evenement no $evenement->getId()",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème de préparation du formulaire est survenu. 
              Veuillez réessayer ultérieurement.");
            return $this->redirectToRoute('admin.evenement.index');
        }
        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve data from form with handleRequest for evenenement no $evenement->getId()",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu et l'évènement n'a pas pu être mis à jour. 
              Veuillez réessayer plus tard.");
            return $this->redirectToRoute('admin.evenement.index');
        }
        if ($form->isSubmitted() && $form->isValid()) {
        /*   // On récupère l'image transmise
            $image = $form->get('imageFile')->getData();
            if ($image) {
                // On génère un nouveau nom de fichier
                //$safeFilename = $slugger->slug($image);
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            // On crée l'image dans la base de données
            $evenement->setImage($fichier);
            */
            try {
                $evenement->setUpdatedAt(new Datetime());
                $this->em->flush(); // mise à jour de la base
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to update in evenement table element with id $id)",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème est survenu et l'évènement n'a pas pu être modifié. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('admin.evenement.index');
            }
            
            $this->addFlash('success', "Evenement modifié avec succés");
            // On redirige l'utilisateur vers la liste des événements
            return $this->redirectToRoute('admin.evenement.index');
        }
        return $this->render('admin/evenement/edit.html.twig', [
        'title' => 'Edition', 'titre' => 'Edition d\'un événement',  'current_menu' => 'admin', 'evenement' => $evenement, 'form' => $form->createView()  ]);
    }

    /**
     * Deletes an event from storage after checking token is valid
     * 
     * @Route("/admin/evenement/delete/{id}", name="admin.evenement.delete", methods="DELETE")
     *
     * @param int $id the event id to be deleted
     * @param Request $request contains the token
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(int $id, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration sur ce site.");
            return $this->redirectToRoute('evenements');
        }
        // ajout d'un contrôle de token pour la sécurité. On le récupère dans la request
        if ($this->isCsrfTokenValid('delete' . $id, $request->get('_tocken'))) {
            try {
                $event = $this->repository->findOneById($id);
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to retrieve from evenement table with findOneById($id)",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème d'accès aux actualités est survenu. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('admin.evenement.index');
            }
            try {
                $this->em->remove($event);
                $this->em->flush();
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to delete element with id $id from evenement table",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème est survenu et l'évènement n'a pas pu être supprimé. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('admin.evenement.index');
            }
            
            $this->addFlash('success', "Evenement supprimé avec succés");
        }
        return $this->redirectToRoute('admin.evenement.index');
    }
}
