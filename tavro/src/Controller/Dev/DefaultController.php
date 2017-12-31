<?php

namespace App\Controller\Dev;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Exception\ApiInvalidPayloadException;
use App\Exception\Api\ApiException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\Api\ApiRequestLimitException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;
use App\Model\HandlerInterface\EntityHandlerInterface;

class DefaultController extends Controller
{

    /**
     * Default "index" page for the API, only accessible in DEV environments
     * as a placeholder for basic benchmarks and such.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();

        return $this->render('TavroApiBundle:Default:index.html.twig', [
            'phpinfo' => $phpinfo
        ]);
    }
}