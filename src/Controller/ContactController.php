<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\MailerService;

class ContactController extends AbstractController
{
    
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerService $mailerService)
    {
        
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $mailerService->sendEmail($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
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
