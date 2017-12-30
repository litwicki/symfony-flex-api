<?php namespace Tavro\Exception\Entity\TavroUser;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author jake@zoadilack.com
 */
class UserNotActivatedException extends HttpException
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
        parent::__construct($code, $message, $previous, array(), $code);
    }
}
