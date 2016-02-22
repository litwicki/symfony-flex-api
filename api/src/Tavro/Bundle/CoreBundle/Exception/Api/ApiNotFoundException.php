<?php

namespace Tavro\Bundle\CoreBundle\Exception\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author jake@zoadilack.com
 */
class ApiNotFoundException extends HttpException
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
        parent::__construct(404, $message, $previous, array(), $code);
    }
}