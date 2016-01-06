<?php

namespace Tavro\Bundle\AppBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Tavro\Bundle\CoreBundle\Entity\File;

class FileController extends Controller
{

    public function downloadAction(Request $request, File $file)
    {

        if(false === $this->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You must be logged in to download this file!');
        }

        $url = $file->getAwsUrl();

        $file_headers = @get_headers($url);

        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            throw new NotFoundHttpException('The file you were attempting to download does not exist!');
        }
        else {

            $page = array(
                'file' => $file,
            );

            return $this->render('TavroAppBundle:File:download.html.twig', $page);

        }

    }

}