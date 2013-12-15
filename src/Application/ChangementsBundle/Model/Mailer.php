<?php

namespace Application\ChangementsBundle\Model;
use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    protected $mailer;

    protected $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;

        $this->templating = $templating;
    }

    public function sendContactMessage($contact)
    {
        $template = 'ApplicationChangementsBundle:templates:email.txt.twig';

        $from = $contact->getEmail();

        $to = 'admin@example.com';

        $subject = '[benjamin.leveque.me] Formulaire de contact';

        $body = $this->templating->render($template, array('contact' => $contact));

        $this->sendMessage($from, $to, $subject, $body);
    }

    protected function sendMessage($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');

        $this->mailer->send($mail);
    }
}


