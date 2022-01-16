<?php

namespace App\Manager;

use App\Entity\Mailing;
use App\Services\EntityServices;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactManager extends AbstractManager
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(EntityServices $entityServices, MailerInterface $mailer, ParameterBagInterface $parameterBag)
    {
        parent::__construct($entityServices);
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;
    }

    public function sendMailFromClient(Request $request)
    {
        $email = (new Email())
            ->from($request->get('email'))
            ->to($this->parameterBag->get('site_email'))
            ->subject($request->get('subject'))
            ->text($request->get('message'));

        $this->mailer->send($email);

        $mail = new Mailing();
        $mail->setFirstname($request->get('firstname'));
        $mail->setLastname($request->get('lastname'));
        $mail->setSubject($request->get('subject'));
        $mail->setMail($request->get('email'));
        $mail->setMessage($request->get('message'));

        $this->entityServices->save($mail);
    }
}
