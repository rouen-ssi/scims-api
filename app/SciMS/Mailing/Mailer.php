<?php
/**
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-rouen.fr>
 */

namespace SciMS\Mailers;


abstract class Mailer
{
    /**
     * @return string
     */
    public abstract function getDestination();

    /**
     * @return string
     */
    public abstract function getSubject();

    /**
     * @return void
     */
    protected abstract function renderAsHtml();

    /**
     * @return string
     */
    public function getBodyAsHtml()
    {
        ob_start();
        $this->renderAsHtml();
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }
}