<?php

namespace Tavro\Bundle\CoreBundle\Exception\Form;

/**
 * Class InvalidFormException
 * @author: jake.litwicki
 *
 * @package Tavro\Bundle\CoreBundle\Exception\Form
 */
class InvalidFormException extends \RuntimeException
{
    protected $form;

    public function __construct($message, $form = NULL)
    {
        parent::__construct($message);
        $this->form = $form;
    }

    /**
     * @return array|null
     */
    public function getForm()
    {
        return $this->form;
    }
}