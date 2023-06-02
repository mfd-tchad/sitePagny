<?php

namespace App\Controller\Admin;

use Exception;
use App\Entity\User;
use App\Form\UserType;
use Psr\Log\LoggerInterface;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserController extends AbstractController
{
    /**
     * @var UserRepository
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

    public function __construct(UserRepository $repository, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->logger = $logger;
    }
    /**
     * @Route("/admin/user", name="admin.user.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration des utilisateurs.");
            return $this->redirectToRoute('evenements');
        }
        try {
            $users = $this->repository->findAll();
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from user table with findAll()",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux utilisateurs est survenu. 
              Veuillez réessayer ultérieurement.");
        }
        return $this->render('admin/user/index.html.twig', [
            'title' => 'Admin', 'titre' => 'Administration des utilisateurs',
            'current_menu' => 'admin', 'users' => $users]);
    }

    /**
     * intended to be used to create a new user.
     * 
     * @Route("/admin/user/create", name="admin.user.new")
     */
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration des utilisateurs.");
            return $this->redirectToRoute('events');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve data from form with handleRequest for new user",
                ['exception' => $e],
            );
            $this->addFlash(
                'danger',
                "Oups ! Un problème est survenu dans la gestion du formulaire et l'utilisateur n'a pas pu être créé. 
              Veuillez réessayer ultérieurement"
            );
            return $this->redirectToRoute('admin.event.index');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // no password at this stage, what do we do ?
            // create one temp and the user will reset it
            $plainPassword = $user->getUsername() . random_int(1, 10000);

            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $plainPassword
                )
            );

            try {
                $this->em->persist($user);
                $this->em->flush();
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to persist or flush a new user in user table)",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème est survenu et l'utilisateur n'a pas pu être créé. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('admin.user.index');
            }

            // do anything else you need here, like send an email
            $this->addFlash('success', "Nouvel utilisateur créé avec succés");
            // On redirige l'administrateur vers la liste des utilisateurs
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/new.html.twig', [
            'title' => 'Admin', 'titre' => 'Enregistrement d\'un utilisateur',
            'current_menu' => 'admin',
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin.user.edit", methods="GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(int $id, Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration des utilisateurs.");
            return $this->redirectToRoute('events');
        }

        try {
            $user = $this->repository->findOneById($id);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from user table with findOneById($id)",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès aux utilisateurs est survenu. 
              Veuillez réessayer ultérieurement.");
        }
        $form = $this->createForm(UserType::class, $user);
        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $username = $user->getUsername();
            $this->logger->critical(
                "Failed to retrieve data from form with handleRequest for user $username",
                ['exception' => $e],
            );
            $this->addFlash(
                'danger',
                "Oups ! Un problème est survenu dans la gestion du formulaire et l'utilisateur n'a pas pu être mis à jour. 
              Veuillez réessayer ultérieurement"
            );
            return $this->redirectToRoute('admin.event.index');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->flush(); // mise à jour de la base
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to flush a user in user table)",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème est survenu et l'utilisateur n'a pas pu être modifié. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('admin.user.index');
            }
            $this->addFlash('success', "Utilisateur modifié avec succés");
            // On redirige l'utilisateur vers la liste des événements
            return $this->redirectToRoute('admin.user.index');
        }
        return $this->render('admin/user/edit.html.twig', [
        'title' => 'Admin', 'titre' => 'Edition d\'un utilisateur',
        'current_menu' => 'admin',
        'user' => $user,
        'form' => $form->createView()  ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin.user.delete", methods={"POST|DELETE"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(int $id, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration des utilisateurs.");
            return $this->redirectToRoute('events');
        }

        // ajout d'un contrôle de tocken pour la sécurité. On le récupère dans la request
        if ($this->isCsrfTokenValid('delete' . $id, $request->get('_tocken'))) {
            try {
                $user = $this->repository->findOneById($id);
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to retrieve from user table with findOneById($id)",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème d'accès aux utilisateurs est survenu. 
                  Veuillez réessayer ultérieurement.");
            }
            try {
                $this->em->remove($user);
                $this->em->flush();
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to delete user with id $id from user table",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème est survenu et l'utilisateur n'a pas pu être supprimé. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('admin.event.index');
            }
            $this->addFlash('success', "Utilisateur supprimé avec succés");
        } else {
            $this->logger->critical(
                "CsfrToken is not valid for user with id $id)",
                [],
            );
            $this->addFlash('danger', "Echec de la suppression de l'utilisateur");
        }
        return $this->redirectToRoute('admin.user.index');
    }
}
