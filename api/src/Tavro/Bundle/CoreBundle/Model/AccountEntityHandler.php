<?php

namespace Tavro\Bundle\CoreBundle\Model;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Validator\RecursiveValidator as Validator;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use Litwicki\Common\Common;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestSizeException;
use Tavro\Bundle\CoreBundle\Model\EntityHandlerInterface;
use Tavro\Bundle\CoreBundle\Component\Form\FormErrors;
use Tavro\Bundle\CoreBundle\Model\EntityHandler;

class AccountEntityHandler extends EntityHandler implements EntityHandlerInterface
{
    /**
     * Find all Entities (limit the response size)
     * Optionally page the result set by LIMIT and OFFSET.
     *
     * @param array $params
     *
     * @throws \Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException
     * @throws \Exception
     * @return array|void
     */
    public function findAll(array $params = array())
    {
        try {

            $accounts = $this->getMyAccounts();
            $items = [];
            $count = 0;

            $page = isset($params['page']) ? $params['page'] : 1;
            $size = isset($params['size']) ? $params['size'] : parent::PAGE_SIZE;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'id';

            $sortOrder = array($orderBy => $sort);

            $offset = ($page - 1) * $size;

            $params = $this->filterParams($params);

            //default the status to ACTIVE
            if(!isset($params['status'])) {
                $params['status'] = parent::STATUS_ACTIVE;
            }

            foreach($accounts as $account) {

                $entities = $this->repository->findAllByAccount($account, $size, $offset, $params);

                foreach($entities as $entity) {
                    if($this->auth->isGranted('view', $entity)) {
                        $items[$entity->getAccount()->getId()][] = $entity;
                        $count++;
                    }
                }

            }

            return array(
                'data' => $items,
                'message' => sprintf('%s %s items retrieved.', $count, $this->entityClass),
            );

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }
}