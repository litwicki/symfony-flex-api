<?php

namespace Tavro\Handler;

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

use Tavro\Entity\Account;
use Tavro\Exception\Form\InvalidFormException;
use Tavro\Entity\User;
use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Exception\Api\ApiException;
use Tavro\Exception\Api\ApiNotFoundException;
use Tavro\Exception\Api\ApiRequestLimitException;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Api\ApiRequestSizeException;
use Tavro\Component\Form\FormErrors;
use Tavro\Handler\EntityHandler;
use Tavro\Repository\TavroRepositoryInterface;

class AccountEntityHandler extends EntityHandler
{
    /**
     * Verify we have a repository for the Entity Class.
     *
     *      Overrides EntityHandler::getRepository()
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     * @throws \Exception
     */
    public function getRepository()
    {
        return $this->om->getRepository($this->entityClass);
    }

    /**
     * Get all Entities for an Account.
     *
     * @param \Tavro\Entity\Account $account
     * @param array $params
     *
     * @return array
     */
    public function getAllByAccount(Account $account, array $params = array())
    {
        try {

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

            $entities = $this->getRepository()->findAllByAccount($account, $size, $offset, $params);
            $total = $this->getRepository()->getCountOfAllByAccount($account);

            $start = $offset+1;
            $end = ($total > $size) ? $offset + $size : $total;

            return array(
                'data' => $entities,
                'message' => sprintf('Displaying %s %s - %s of %s total.',
                    str_replace('Tavro\\Bundle\\CoreBundle\\Entity\\', '', Inflector::pluralize($this->entityClass)),
                    $start,
                    $end,
                    $total
                )
            );

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Find all Entities (limit the response size)
     * Optionally page the result set by LIMIT and OFFSET.
     *
     * @param array $params
     *
     * @throws \Tavro\Exception\Api\ApiAccessDeniedException
     * @throws \Exception
     * @return array
     */
    public function getAll(array $params = array())
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