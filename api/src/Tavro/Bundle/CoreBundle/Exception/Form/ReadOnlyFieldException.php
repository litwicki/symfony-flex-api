<?php

namespace Tavro\Bundle\CoreBundle\Exception\Form;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ReadOnlyFieldException
 *
 * @package Tavro\Bundle\CoreBundle\Exception\Form
 */
class ReadOnlyFieldException extends HttpException
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
        parent::__construct(403, $message, $previous, array(), $code);
    }
}
