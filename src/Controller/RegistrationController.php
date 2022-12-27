<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

    public function __construct(UserRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/supadmin", name="admin.utilisateur.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index () : Response {
        
        $this->denyAccessUnlessGranted('ROLE_SUPADMIN');
        $users = $this->repository->findAll();
        return $this->render('admin/utilisateur/index.html.twig', [
            'title' => 'Admin', 'titre' => 'Administration des utilisateurs',  'current_menu' => 'admin', 'users' => $users]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->em->persist($user);
            $this->em->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', "Nouvel utilisateur créé avec succés");
            return $this->redirectToRoute('admin.utilisateur.index');  // On redirige l'administrateur vers la liste des utilisateurs
        }

        return $this->render('registration/register.html.twig', [
            'title' => 'Admin', 'titre' => 'Enregistrement d\'un utilisateur',  'current_menu' => 'admin', 'registrationForm' => $form->createView(),
        ]);
    }

    /**
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
        return $this->render('admin/utilisateur/edit.html.twig', [
        'title' => 'Admin', 'titre' => 'Edition d\'un utilisateur',  'current_menu' => 'admin', 'user' => $user, 'form' => $form->createView()  ]);

    }

    /**
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
