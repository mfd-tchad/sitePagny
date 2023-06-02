<?php
namespace App\Service;

use App\Entity\Contact;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerService extends AbstractController {

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger) {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    // generic funtion to send an email
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void {
        $email = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate('emails/' . $template . '.html.twig')
        ->context($context);

        $this->mailer->send($email);
    }

    /**
     * Send an email to Web master from Contact Page
     *
     * @param Contact $contact
     * @return void
     */
    public function sendEmail(Contact $contact): void
    {
        $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('mairie@pagnylablanchecote.net')
            ->replyTo($contact->getEmail())
            ->subject($contact->getSujet())
            ->htmlTemplate('emails/contact.html.twig')
            ->context(['contact' => $contact ]);
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->text('Sending emails is fun again!')
            //->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical(
                "Failed to send mail in sendMail",
                ['exception' => $e],
            );
            $this->addFlash('danger', "Oups ! Un problème est survenu et votre mail n'a pas pu être envoyé. 
              Veuillez réessayer ultérieurement.");
        }
        $this->addFlash('success', 'Votre email a bien été envoyé');
    }
}