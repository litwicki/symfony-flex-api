<?php

namespace Tavro\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Exception\Api\ApiException;
use Tavro\Exception\Api\ApiNotFoundException;
use Tavro\Exception\Api\ApiRequestLimitException;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Entity\Expense;
use Tavro\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;

use Tavro\Entity\Tag;

use Litwicki\Common\Common;
use Tavro\Controller\Api\EntityApiController;

class TagController extends EntityApiController
{

    /**
     * Post (create) a new Tag
     * But first... see if this Tag already exists, and if it does, return it for use.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function postAction(Request $request, $entity, $_format)
    {
        $data = null;

        try {

            $handler = $this->getHandler($entity);
            $data = $this->getPayload($request);

            $tag = $handler->findByTag($data['tag']);

            if(!$tag instanceof Tag) {
                $tag = $handler->post($request, $data);
                $message = sprintf('Tag `%s` already exists, we fetched it for you.', $data['tag']);
            }
            else {
                $message = sprintf('Tag `%s` created!', $data['tag']);
            }

            $data = $tag;

            $options = [
                'format' => $_format,
                'code' => Response::HTTP_CREATED,
                'message' => $message,
            ];
        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

}