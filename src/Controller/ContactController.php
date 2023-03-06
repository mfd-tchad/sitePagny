<?php

namespace App\Controller;

use Exception;
use App\Entity\Contact;
use App\Form\ContactType;
use Psr\Log\LoggerInterface;
use App\Notification\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerService $mailerService)
    {
        
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        try {
            $form->handleRequest($request);
        } catch (Exception $e) {
            $this->logger->critical(
                "Failed to retrieve contact data from form",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu. 
              Veuillez réessayer ultérieurement.");
        }


        if ($form->isSubmitted() && $form->isValid()) {
            
            $mailerService->sendEmail($contact);
            
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'title' => 'Contact', 'titre' => 'Contact',  'current_menu' => 'contact', 'form' => $form->createView()
        ]);
    }

    private function create_form() : Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
       
        return $this->render('contact/index.html.twig', [
            'title' => 'Contact', 'titre' => 'Contact',  'current_menu' => 'contact', 'form' => $form->createView()
        ]);
    }

    
}
