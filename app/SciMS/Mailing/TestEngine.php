<?php
/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */


namespace SciMS\Mailing;


use SciMS\Mailers\Mailer;
use SciMS\Mailers\MailerEngine;

class TestEngine implements MailerEngine
{
    /**
     * @var array
     */
    private $sent;

    public function send(Mailer $mailer)
    {
        $this->sent[] = $mailer;
    }

    /**
     * @return Mailer
     */
    public function pop()
    {
        return array_pop($this->sent);
    }
}