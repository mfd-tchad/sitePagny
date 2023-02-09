<?php

namespace App\Controller;

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

class RegistrationController extends AbstractController
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
     * Abandonned - Replaced by "admin.user index"
     * 
     * @Route("/supadmin", name="admin.utilisateur.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index () : Response {
        
        $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        $users = $this->repository->findAll();
        return $this->render('admin/user/index.html.twig', [
            'title' => 'Admin', 'titre' => 'Administration des utilisateurs',  'current_menu' => 'admin', 'users' => $users]);
    }

    /**
     * Used to create a new user. 
     * 
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        } catch (Exception $e) {
            $this->addFlash('danger', "Désolé, Vous n'avez pas les droits d'administration des utilisateurs.");
            return $this->redirectToRoute('events');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
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
            return $this->redirectToRoute('admin.evenement.index');
        }


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
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
            return $this->redirectToRoute('admin.user.index');  // On redirige l'administrateur vers la liste des utilisateurs
        }

        return $this->render('registration/register.html.twig', [
            'title' => 'Admin', 'titre' => 'Enregistrement d\'un utilisateur',  'current_menu' => 'admin', 'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Old edit function replaced by admin.user.edit in AdminUserController
     * @Route("/admin/utilisateur/{id}", name="admin.utilisateur.edit", methods="GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(User $user, Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->flush(); // mise à jour de la base
            $this->addFlash('success', "Utilisateur modifié avec succés");
            return $this->redirectToRoute('admin.utilisateur.index');  // On redirige l'utilisateur vers la liste des événements
        }
        return $this->render('admin/user/edit.html.twig', [
        'title' => 'Admin', 'titre' => 'Edition d\'un utilisateur',  'current_menu' => 'admin', 'user' => $user, 'form' => $form->createView()  ]);

    }

    /**
     * Old delete function. replaced by admin.user.delete in AdminUserController
     * 
     * @Route("/admin/utilisateur/{id}", name="admin.utilisateur.delete", methods="DELETE")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(UserPasswordHasherInterface $passwordHasher, User $user, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        // ajout d'un conrôle de tocken pour la sécurité. On le récupère dans la request
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_tocken'))) {
        $this->em->remove($user);
        $this->em->flush();
        $this->addFlash('success', "Utilisateur supprimé avec succés");
        } else {
            $this->addFlash('danger', "Echec de la suppression de l'utilisateur");
        }
        return $this->redirectToRoute('admin.utilisateur.index');
    }
    
}
