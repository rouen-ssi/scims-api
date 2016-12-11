<?php
/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */

namespace SciMS\Mailers;


interface MailerEngine
{
    function send(Mailer $mailer);
}