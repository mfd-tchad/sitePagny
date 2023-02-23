<?php
namespace App\Notification;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
// use Twig\Environment;

use App\Entity\Contact;

class MailerService extends AbstractController {

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
        //$this->renderer = $renderer;
    }

    public function sendEmail(Contact $contact): void
    {
        $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('pagnylablanchecote@wanadoo.fr')
            ->replyTo($contact->getEmail())
            ->subject($contact->getSujet())
            ->htmlTemplate('emails/contact.html.twig')
            ->context(['contact' => $contact ]);
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->text('Sending emails is fun again!')
            //->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);

        // ...
    }
}
