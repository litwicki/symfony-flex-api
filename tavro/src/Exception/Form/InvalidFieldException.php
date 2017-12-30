<?php

namespace Tavro\Exception\Form;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class InvalidFieldException
 * @author: jake.litwicki
 *
 * @package Tavro\Exception\Form
 */
class InvalidFieldException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string $message      The internal exception message
     * @param \Exception $previous The previous exception
     * @param int $code            The internal exception code
     */
    public function __construct($message = NULL, \Exception $previous = NULL, $code = 0)
    {
        parent::__construct(500, $message, $previous, array(), $code);
    }
}
