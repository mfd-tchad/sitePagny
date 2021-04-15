<?php
namespace App\Notification;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

use App\Entity\Contact;

class ContactNotification extends AbstractController {

    /**
     * @var \Swift_mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer) {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact) {
        $message = (new \Swift_Message($contact->getSujet()))
                ->setFrom('mairie@pagnylablanchecote.net')
                ->setTo('mairie@pagnylablanchecote.net')
                ->setReplyTo($contact->getEmail())
                ->setBody($this->render('emails/contact.html.twig' , [
                    'contact' => $contact
                ]), 'text/html');

        $this->mailer->send($message);
    }
}