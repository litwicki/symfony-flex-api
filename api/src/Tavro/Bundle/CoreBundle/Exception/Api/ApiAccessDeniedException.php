<?php

namespace Tavro\Bundle\CoreBundle\Exception\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author jake@zoadilack.com
 */
class ApiAccessDeniedException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string $message      The internal exception message
     * @param \Exception $previous The previous exception
     * @param int $code            The internal exception code
     */
    public function __construct($message = NULL, \Exception $previous = NULL, $code = 403)
    {
        parent::__construct(403, $message, $previous, array(), $code);
    }
}
