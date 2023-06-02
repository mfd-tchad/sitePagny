<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Form\UserType;
use App\Service\JWTService;
use Psr\Log\LoggerInterface;
use App\Form\AddEmailFormType;
use App\Service\MailerService;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

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
     * @Route("/profile", name="register.user.profile")
     */
    public function showProfile()
    {
        // Only give access if self
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (Exception $e) {
            $this->addFlash('danger', "Veuillez vous connecter pour avoir accès à votre profil");
            return $this->redirectToRoute('login');
        }
        return $this->render('user/profile.html.twig', [
            'title' => 'Profile', 'titre' => 'Votre profil',  'current_menu' => 'session']);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        FormLoginAuthenticator $formLoginAuthenticator,
        MailerService $mail,
        JWTService $jwt
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve register data from form",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu. 
              Veuillez réessayer de vous enregistrer ultérieurement.");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password and set it to user
            $user->setPassword(
                $passwordHasher->hashPassword(
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
                $this->addFlash('danger', "Oups ! Un problème est survenu et votre enregistrement a échoué. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('alaune');
            }

            // do anything else you need here, like send an email

            // on génère le Json Web token
            // d'abord on crée le header
            $header = [
                'typ' => 'JWT',
                'alg' => 'SH256'
            ];
            // on crée le payload
            $payload = [
                'user_id' => $user->getId()
            ];
            // on génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            try {
                $mail->send(
                    'no-reply@pagnylablanchecote.net',
                    $user->getEmail(),
                    'Activation de votre compte sur le site de Pagny la Blanche Côte',
                    'register',
                    compact('user', 'token')
                );
                $this->addFlash('success', "Merci pour votre enregistrement, 
                veuillez consulter vos mails pour confirmer votre inscription");

            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to send register confirmation email",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Votre enregistrement a bien eu lieu mais le mail pour
                    confirmer votre inscription n'a pas pu vous être envoyé. 
                  Veuillez demander un renvoi de l'activation de votre compte");
            }

            
            // On redirige l'utilisateur vers la page d'accueil, idéalement, peut-être un espace membre
            // return $this->redirectToRoute('home');
            return $userAuthenticator->authenticateUser(
                $user,
                $formLoginAuthenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'title' => 'Admin', 'titre' => 'Inscrivez-vous pour accéder à plus d\'infos',
            'current_menu' => 'session',
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/addemail", name="register.user.addemail")
     * #This function is called when the user has been created by the SUP_ADMIN and has therefore no email
     * #address. The user is asked to enter one when entering in his profile
     */
    public function addEmail(
        Request $request
    ): Response {
        // Only give access if self
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (Exception $e) {
            $this->addFlash('danger', "Veuillez vous connecter pour avoir accès à votre profil");
            return $this->redirectToRoute('login');
        }
        $user = $this->getUser();
        $form = $this->createForm(AddEmailFormType::class, $user);

        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve email address from form for user $user->getFirstname() $user->getLastname()",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu. 
              Veuillez réessayer ultérieurement.");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->flush();
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to flush new email address for user $user->getUsername() in user table)",
                    ['exception' => $e],
                );
                $this->addFlash(
                    'danger',
                    "Oups ! Un problème est survenu et l'enregistrement de votre adresse mail a échoué. 
                    Veuillez réessayer ultérieurement."
                );
                return $this->redirectToRoute('register.user.profile');
            }
            $this->addFlash('success', 'Adresse mail ajoutée avec succès');
            return $this->redirectToRoute(('register.user.profile'));
        }
        return $this->render('user/add_email.html.twig', [
            'title' => 'Add email', 'titre' => 'Ajouter votre adresse mail',
            'current_menu' => 'session',
            'form' => $form->createView()]);
    }

    /**
     * @Route("/register/changeemail", name="register.user.changeemail")
     * #This function is called by the user himself
     */
    public function changeEmail(
        Request $request
    ): Response {
        // Only give access if self
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (Exception $e) {
            $this->addFlash('danger', "Veuillez vous connecter pour avoir accès à votre profil");
            return $this->redirectToRoute('login');
        }
        $user = $this->getUser();
        $form = $this->createForm(AddEmailFormType::class, $user);

        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve email address from form for user $user->getFirstname() $user->getLastname()",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu. 
              Veuillez réessayer ultérieurement.");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->flush();
            } catch (Exception $e) {
                $this->logger->critical(
                    "Failed to flush new email address for user $user->getUsername() in user table)",
                    ['exception' => $e],
                );
                $this->addFlash(
                    'danger',
                    "Oups ! Un problème est survenu et votre nouvelle adresse mail n'a pas pu être enregistrée. 
                    Veuillez réessayer ultérieurement."
                );
                return $this->redirectToRoute('register.user.profile');
            }
            $this->addFlash('success', 'Adresse mail remplacée avec succès');
            return $this->redirectToRoute(('register.user.profile'));
        }
        return $this->render('user/add_email.html.twig', [
            'title' => 'Change email address', 'titre' => 'Remplacer votre adresse mail',
            'current_menu' => 'session',
            'form' => $form->createView()]);
    }

    /**
     * @Route("/register/changepwd", name="register.user.changepwd")
     * #This function is called by the user himself
     */
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Only give access if self
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (Exception $e) {
            $this->addFlash('danger', "Veuillez vous connecter pour avoir accès à votre profil");
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ChangePasswordFormType::class);

        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve new Password from form",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu. 
              Veuillez réessayer ultérieurement.");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // verify old password
            // tell the controller what $user is
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            if ($passwordHasher->isPasswordValid($user, $form->get('oldPassword')->getData())) {
                // if ever there is an error red underline under setPassword, it doesn't matter
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );

                try {
                    $this->em->flush();
                } catch (Exception $e) {
                    $username = $user->getUserIdentifier();
                    $this->logger->critical(
                        "Failed to flush new email address for user $username in user table)",
                        ['exception' => $e],
                    );
                    $this->addFlash(
                        'danger',
                        "Oups ! Un problème est survenu et votre nouvelle adresse mail n'a pas pu être enregistrée. 
                        Veuillez réessayer ultérieurement."
                    );
                    return $this->redirectToRoute('register.user.profile');
                }
                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute(('register.user.profile'));
            } else {
                $this->addFlash('danger', 'Le mot de passe actuel est incorrect');
            }
        }
        return $this->render('security/change_password.html.twig', [
            'title' => 'Change password', 'titre' => 'Changer le mot de passe',
            'current_menu' => 'session',
            'form' => $form->createView()]);
    }

    /**
     * @Route("/verif/{token}", name="register.user.verify")
     */
    public function verifyUser(string $token, JWTService $jwt): Response
    {
        // on vérifie si le token est valide, n'a pas expiré, et n'a pas été modifié
        if (
            $jwt->isValid($token) && !$jwt->isExpired($token)
            && $jwt->check($token, $this->getParameter('app.jwtsecret'))
        ) {
            // on récupère le payload
            $payload = $jwt->getPayload($token);

            // on récupère le user du token
            try {
                $user = $this->repository->findOneById($payload['user_id']);
            } catch (Exception $e) {
                $userId = $payload['user_id'];
                $this->logger->critical(
                    "Failed to retrieve from user table with findOneById($userId)",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème d'accès à votre enregistrement utilisateur est survenu. 
                    Veuillez réessayer l'activation ultérieurement.");
            }

            // on vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                try {
                    $this->em->flush();
                } catch (Exception $e) {
                    $this->logger->critical(
                        "Failed to flush isVerified for user $user->getUserIdentifier() in user table)",
                        ['exception' => $e],
                    );
                    $this->addFlash(
                        'danger',
                        "Oups ! Un problème est survenu et l'activation a échoué. 
                        Veuillez réessayer ultérieurement."
                    );
                    return $this->redirectToRoute('register.user.profile');
                }
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('register.user.profile');
            }
        }
        // ici un problème se pose dans le token
        $this->addFlash('danger', "Le token est invalide ou a expiré. Veuillez renouveler l'activation");
        return $this->redirectToRoute('register.user.profile');
    }

    /**
     * @Route("/register/verif/resend", name="register.verif.resend")
     */
    public function resendVerif(JWTService $jwt, MailerService $email): Response
    {
        /* tell the function what $user is in order to avoid error red line when calling
            a function attribute (getIsVerified)
        */
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash(
                'danger',
                'Vous devez être connecté pour renvoyer le lien d\'activation'
            );
            return $this->redirectToRoute('login');
        }
        // if ever there is an error red underline under getIsVerified, it doesn't matter
        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('register.user.profile');
        }
        // on génère le Json Web token
        // d'abord on crée le header
        $header = [
            'typ' => 'JWT',
            'alg' => 'SH256'
        ];
        // on crée le payload
        $payload = [
            'user_id' => $user->getId()
        ];
        // on génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        try {
            $email->send(
                'no-reply@pagnylablanchecote.net',
                $user->getEmail(), // if ever there is an error red underline under getEmail, it doesn't matter
                'Activation de votre compte sur le site de Pagny la Blanche Côte',
                'register',
                compact('user', 'token')
            );
            $this->addFlash('success', "Le nouveau mail d'activation a été envoyé, 
            veuillez consulter vos mails pour confirmer votre inscription");
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to send register confirmation email",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu et le mail pour
                confirmer votre inscription n'a pas pu vous être envoyé. 
              Veuillez réessayer ultérieurement");
        }

        // On redirige l'utilisateur vers la page d'accueil, idéalement, peut-être un espace membre
        return $this->redirectToRoute('register.user.profile');
    }

    /**
     * Intended to allow user to modify his profile. Not used yet
     * 
     * @Route("/register/user/edit", name="register.user.edit", methods="GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        // Only give access if self or ROLE_SUP_ADMIN
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (Exception $e) {
            $this->addFlash('danger', "Veuillez vous connecter pour avoir accès à votre profil");
            return $this->redirectToRoute('login');
        }
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve user data from form for user $user->getFirstname() $user->getLastname()",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu. 
              Veuillez réessayer ultérieurement.");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush(); // mise à jour de la base
            $this->addFlash('success', "Votre profil a bien été modifié");
            return $this->redirectToRoute('register.user.profile');  // On redirige l'utilisateur vers la page de profil
        }
        return $this->render('admin/user/edit.html.twig', [
        'title' => 'Edit profile', 'titre' => 'Modification de votre profil',
        'current_menu' => 'profile',
        'user' => $user,
        'form' => $form->createView()  ]);
    }

    /**
     * @Route("/register/user/delete", name="register.user.delete", methods="DELETE")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(User $user, Request $request)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (Exception $e) {
            $this->addFlash('danger', "Veuillez vous connecter pour avoir accès à votre profil");
            return $this->redirectToRoute('login');
        }
        // ajout d'un conrôle de tocken pour la sécurité. On le récupère dans la request
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_tocken'))) {
            try {
                $this->em->remove($user);
                $this->em->flush();
            } catch (Exception $e) {
                $id = $user->getId();
                $this->logger->critical(
                    "Failed to delete user with id $id from user table",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème technique a empêché de vous supprimer de l'espace membre. 
                  Veuillez réessayer ultérieurement.");
                return $this->redirectToRoute('register.user.profile');
            }
            $this->addFlash('success', "Vous êtes désormais supprimé de l'espace membres");
        } else {
            $id = $user->getId();
            $this->logger->critical(
                "Failed to delete user with id $id from user table"
            );
            $this->addFlash('danger', "Désolé, une erreur a empêché de vous supprimer de l'espace membres");
            return $this->redirectToRoute('register.user.profile');
        }
        return $this->redirectToRoute('home');
    }
    
}
