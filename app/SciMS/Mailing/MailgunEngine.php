<?php
/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */

namespace SciMS\Mailers;

use Mailgun\Mailgun;


class MailgunEngine implements MailerEngine
{
    /**
     * @var Mailgun
     */
    private $mg;

    public function __construct()
    {
        $this->mg = new Mailgun(MAILGUN_KEY);
    }

    public function send(Mailer $mailer)
    {
        $this->mg->sendMessage(MAILGUN_DOMAIN, [
            'from' => MAILGUN_FROM,
            'to' => $mailer->getDestination(),
            'subject' => $mailer->getSubject(),
            'html' => $mailer->getBodyAsHtml(),
        ]);
    }
}