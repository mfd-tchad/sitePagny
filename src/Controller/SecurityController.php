<?php
namespace App\Controller;
use Exception;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Service\MailerService;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController {

    /**
     * LoggerInterface will be used to log errors and warnings to console or log file
     *
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils) {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'title' => 'Login', 'current_menu' => 'session',
            'last_username' => $lastUsername,
            'error' => $error
            ]);
    }

    /**
     * @Route("/forgottenpwd", name="forgotten_password")
     */
    public function forgottenPassword(
        Request $request,
        UserRepository $repository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        MailerService $mail
    ): Response {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // fetch the user by his username
            try {
                $user = $repository->findOneByUsername($form->get('username')->getData());
            } catch (Exception $e) {
                $username = $form->get('username')->getData();
                $this->logger->critical(
                    "Failed to retrieve from user table with findOneByUsername($username)",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème d'accès à votre enregistrement utilisateur est survenu. 
                    Veuillez réessayer ultérieurement.");
            }

            // on vérifie si on a un utilisateur
            if (!$user) {
                // $user est null
                $this->logger->critical(
                    "null user retrieved from user table with findOneByUsername($username)"
                );
                $this->addFlash('danger', 'Un problème est survenu');
                return $this->redirectToRoute('login');
            } else {
                /* On vérifie que l'adresse mail de récupération du mot de passe saisie est
                identique à celle qui est enregistrée en base de données
                */
                if ($user->getEmail() == $form->get('email')->getData()) {
                    // On génère un token de réinitialisation
                    $token = $tokenGenerator->generateToken();
                    $user->setResetToken($token);
                    try {
                        $entityManager->persist($user);
                        $entityManager->flush();
                    } catch (Exception $e) {
                        $this->logger->critical(
                            "Failed to persist or flush the user with reset_token (to reset password) set in user table)",
                            ['exception' => $e],
                        );
                        $this->addFlash('danger', "Oups ! Un problème est survenu et l'opération a échoué. 
                          Veuillez réessayer ultérieurement.");
                        return $this->redirectToRoute('login');
                    }

                    // On génère un lien de réinitialisation de mot de passe
                    $url = $this->generateUrl(
                        'reset_password',
                        ['token' => $token],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );

                    // On crée les données du mail
                    $context = compact('url', 'user');

                    // on envoie le mail
                    try {
                        $mail->send(
                            'noreply@mfdewulf.fr',
                            $user->getEmail(),
                            'Réinitialisation de mot de passe',
                            'reset_password',
                            $context
                        );
                    } catch (Exception $e) {
                        $email = $user->getEmail();
                        $this->logger->critical(
                            "Failed to send email to $email to re-init password",
                            ['exception' => $e],
                        );
                        $this->addFlash('danger', "Oups ! Un problème est survenu et le mail de réinitialisation du mot de passe n'a pas pu être envoyé. 
                          Veuillez réessayer ultérieurement.");
                        return $this->redirectToRoute('login');
                    }
                    $this->addFlash('success', 'Email envoyé avec succès');
                    return $this->redirectToRoute('login');
                } else {
                    $this->addFlash('danger', "L'adresse email n'est pas la bonne");
                    // on va repartir au formulaire de saisie de l'identifiant et email
                }
            }
        }
        return $this->render('security/reset_password_request.html.twig', [
            'title' => 'Récupération de mot de passe',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/resetpwd{token}", name="reset_password")
     */
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $repository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Vérifier si on trouve ce token dans la B.D.
        try {
            $user = $repository->findOneBy(['reset_token' => $token]);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve from user table with findOneBy(['reset_token' => $token])",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème d'accès à votre enregistrement utilisateur est survenu. 
                Veuillez réessayer ultérieurement.");
        }
        if ($user) {
            // créer un formulaire
            $form = $this->createForm(ResetPasswordFormType::class);
            $username = $user->getUserIdentifier();
            try {
                $form->handleRequest($request);
            } catch (Exception $e) {
                
                $this->logger->critical(
                    "Failed to retrieve user data from form for user $username",
                    ['exception' => $e],
                );
                $this->addFlash('danger', "Oups ! Un problème est survenu. 
                  Veuillez réessayer ultérieurement.");
            }

            if ($form->isSubmitted() && $form->isValid()) {
                // On efface le token
                $user->setResetToken("");
                // encode the plain password and set it to user
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                try {
                    $entityManager->persist($user);
                    $entityManager->flush();
                } catch (Exception $e) {
                    $this->logger->critical(
                        "Failed to persist or flush the user $username with new password in user table)",
                        ['exception' => $e],
                    );
                    $this->addFlash('danger', "Oups ! Un problème est survenu et l'opération a échoué. 
                      Veuillez réessayer ultérieurement.");
                    return $this->redirectToRoute('login');
                }

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('login');
            }

            return $this->render('security/reset_password.html.twig', [
                'title' => 'Nouveau mot de passe',
                'form' => $form->createView()
            ]);
        }
        $this->logger->critical(
            "null user retrieved from user table with findOneBy(['reset_token' => $token])"
        );
        $this->addFlash('danger', "Oups ! Le jeton de réinitialisation est invalide. 
            Veuillez refaire la demande de réinitiliasation.");
        return $this->redirectToRoute('login');
    }

}