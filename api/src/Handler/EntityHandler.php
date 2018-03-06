<?php

namespace App\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Validator\RecursiveValidator as Validator;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use App\Exception\Form\InvalidFormException;
use App\Entity\User;
use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\Api\ApiRequestLimitException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Api\ApiRequestSizeException;
use App\Model\HandlerInterface\EntityHandlerInterface;
use App\Component\Form\FormErrors;
use App\Model\EventInterface\TavroCreateEventInterface;
use App\Model\EventInterface\TavroUpdateEventInterface;
use App\Model\EventInterface\TavroDeleteEventInterface;
use App\Model\EntityInterface\AccountEntityInterface;
use App\Repository\TavroRepository;
use App\Repository\TavroRepositoryInterface;

class EntityHandler implements EntityHandlerInterface
{
    public $om;
    public $entityClass;
    public $repository;
    public $formFactory;
    public $encoderFactory;
    public $tokenStorage;
    public $auth;
    public $validator;
    public $dispatcher;
    public $user;
    public $isAdmin;

    const PAGE_SIZE = 25;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;
    const STATUS_DISABLED = 0;

    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PATCH = 'PATCH';
    const HTTP_METHOD_DELETE = 'DELETE';

    const ACCESS_DENIED_MESSAGE = 'You are not authorized to perform this action!';

    public function __construct(ObjectManager $om, FormFactory $formFactory, Validator $validator, EncoderFactory $encoderFactory, TokenStorage $tokenStorage, AuthorizationChecker $auth, EventDispatcherInterface $dispatcher, $entityClass)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;

        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
        $this->auth = $auth;
        $this->dispatcher = $dispatcher;

        $token = $this->tokenStorage->getToken();

        $this->user = false;
        $this->isAdmin = false;

        if(!is_null($token)) {
            $this->user = $token->getUser();
            $this->isAdmin = $this->auth->isGranted('ROLE_ADMIN');
        }
    }

    /**
     * Verify we have a repository for the Entity Class.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     * @throws \Exception
     */
    public function getRepository()
    {
        return $this->om->getRepository($this->entityClass);
    }

    /**
     * @param \App\Model\EventInterface\TavroUpdateEventInterface $entity
     *
     * @throws \Exception
     */
    public function dispatchUpdateEvent(TavroUpdateEventInterface $entity)
    {
        try {
            $eventClass = $entity::UPDATE_EVENT_CLASS;
            $event = new $eventClass($entity);
            $this->dispatcher->dispatch($eventClass::NAME, $event);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \App\Model\EventInterface\TavroCreateEventInterface $entity
     *
     * @throws \Exception
     */
    public function dispatchCreateEvent(TavroCreateEventInterface $entity)
    {
        try {
            $eventClass = $entity::CREATE_EVENT_CLASS;
            $event = new $eventClass($entity);
            $this->dispatcher->dispatch($eventClass::NAME, $event);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \App\Model\EventInterface\TavroDeleteEventInterface $entity
     *
     * @throws \Exception
     */
    public function dispatchDeleteEvent(TavroDeleteEventInterface $entity)
    {
        try {
            $eventClass = $entity::DELETE_EVENT_CLASS;
            $event = new $eventClass($entity);
            $this->dispatcher->dispatch($eventClass::NAME, $event);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an array of all ACcounts this User should have access to.
     * This includes Organizations they "own" as well as ones they are mere Users of
     */
    public function getMyAccounts()
    {
        try {

            $accounts = array();

            foreach($this->user->getAccountUsers() as $entity) {
                $accounts[$entity->getId()][] = $entity->getAccount();
            }

            return $accounts;

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param integer $id
     *
     * @return object
     * @throws \Exception
     */
    public function get($id)
    {
        try {

            $entity = $this->getRepository()->find($id);

            if($this->auth->isGranted('view', $entity)) {
                return $entity;
            }
            else {
                $message = sprintf('You are not authorized to view this %s.', $this->entityClass, $id);
                throw new ApiAccessDeniedException($message);
            }

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch an array of Entities by Identifier.
     *
     * @param int[] $ids  the ids to return (returns all if null)
     *
     * @return array
     */
    public function findEntities($ids = null)
    {
        try {

            $entities = null;
            $items = array();

            if(is_null($ids)) {
                $entities = $this->getAll();
            }
            else {
                foreach ($ids as $id) {
                    $entities[] = $this->getRepository()->find($id);
                }
            }

            if(!empty($entities)) {
                foreach ($entities as $entity) {
                    if($this->auth->isGranted('view', $entity)) {
                        $items[] = $entity;
                    }
                }
            }

            return $items;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Filter out parameters we don't want to give access to.
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public function filterParams(array $params)
    {
        try {

            $parameters = array();
            $options = array('status');

            foreach($params as $name => $value) {
                if(in_array($name, $options)) {
                    $parameters[$name] = $value;
                }
            }

            return $parameters;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Find all Entities (limit the response size)
     * Optionally page the result set by LIMIT and OFFSET.
     *
     * @param array $params
     *
     * @return array
     */
    public function getAll(array $params = array())
    {
        try {

            $page = isset($params['page']) ? $params['page'] : 1;
            $size = isset($params['size']) ? $params['size'] : $this::PAGE_SIZE;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'id';

            $sortOrder = array($orderBy => $sort);

            if(!isset($params['status'])) {
                $params['status'] = $this::STATUS_ACTIVE; //@TODO: Make this a constant fetched from Model\Entity.php
            }

            $offset = ($page - 1) * $size;

            $params = $this->filterParams($params);

            $entities = $this->getRepository()->findBy(
                $params,
                $sortOrder,
                $size,
                $offset
            );

            $items = array();
            $count = 0;

            foreach($entities as $entity) {

                if($this->auth->isGranted('view', $entity)) {

                    if($entity instanceof AccountEntityInterface) {
                        $items['accounts'][$entity->getAccount()->getId()][] = $entity;
                    }
                    else {
                        $items[] = $entity;
                    }

                    $count++;

                }

            }

            /**
             * Fetch what the total count would be
             */
            $total = $this->getRepository()->getCountOfAll();

            $start = $offset+1;
            $end = ($total > $size) ? $offset + $size : $total;

            return array(
                'data' => $items,
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
     * Return an int count of entities available for a specific class.
     *
     * @returns int $count
     */
    public function getCount()
    {
        try {

            $count = $this->getEntityManager()
                        ->createQuery(sprintf('SELECT COUNT(x.id) FROM %s x', $this->entityClass))
                        ->getSingleScalarResult();

            return $count;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * POST to an Endpoint.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function post(Request $request, array $parameters)
    {
        try {
            return $this->create($request, $parameters);
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(TransformationFailedException $e) {
            throw $e;
        }
        catch(UnexpectedTypeException $e) {
            throw $e;
        }
        catch(InvalidPropertyPathException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new Entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     */
    public function create(Request $request, array $parameters)
    {
        try {

            if(!isset($parameters['status'])) {
                $parameters['status'] = self::STATUS_ACTIVE;
            }

            $entity = $this->createEntity();
            $entity = $this->processForm($request, $entity, $parameters);

//            /**
//             * If this is an ApiEntity immediately save so the slug property
//             * is updated correctly with the entity Id: {id}-{url-save-title}
//             */
//            if($entity instanceof EntityInterface) {
//                $entity = $this->patch($request, $entity, $parameters, self::HTTP_METHOD_PATCH);
//            }

            if($entity instanceof TavroCreateEventInterface) {
                $this->dispatchCreateEvent($entity);
            }

            return $entity;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(TransformationFailedException $e) {
            throw $e;
        }
        catch(UnexpectedTypeException $e) {
            throw $e;
        }
        catch(InvalidPropertyPathException $e) {
            throw $e;
        }
        catch(\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            throw new ApiAccessDeniedException($this::ACCESS_DENIED_MESSAGE);
        }
    }

    /**
     * Edit an Entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     */
    public function put(Request $request, EntityInterface $entity, array $parameters)
    {
        try {

            $entity = $this->processForm($request, $entity, $parameters, self::HTTP_METHOD_PUT);

            if($entity instanceof TavroUpdateEventInterface) {
                $this->dispatchUpdateEvent($entity);
            }

            return $entity;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Delete an Entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     *
     * @throws \Exception
     */
    public function delete(Request $request, EntityInterface $entity)
    {
        try {

            if(!($this->auth->isGranted('delete', $entity))) {
                $message = sprintf('You are not authorized to delete %s!', $entity->__toString());
                throw new ApiAccessDeniedException($message);
            }

            if($entity instanceof TavroDeleteEventInterface) {
                $this->dispatchDeleteEvent($entity);
            }

            $this->om->remove($entity);
            $this->om->flush();

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
            //throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function patch(Request $request, EntityInterface $entity, array $parameters)
    {
        try {

            $this->auth->isGranted('patch', $entity);
            $entity = $this->applyPatch($request, $entity, $parameters);

            /**
             * @TODO: need to add some logic here to determine WHEN to fire the event..
             */

//            if($entity instanceof TavroUpdateEventInterface) {
//                $this->dispatchUpdateEvent($entity);
//            }

            return $entity;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Separate the actual application of the patch parameters, so we can override
     * in individual entities without replicating this code repeatedly.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function applyPatch(Request $request, EntityInterface $entity, array $parameters)
    {
        try {
            return $this->processForm($request, $entity, $parameters, self::HTTP_METHOD_PATCH);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     * @param string $method
     *
     * @throws \Exception
     * @throws \Symfony\Component\Debug\Exception\ContextErrorException
     */
    public function processForm(Request $request, EntityInterface $entity, array $parameters, $method = self::HTTP_METHOD_POST)
    {
        try {

            $this->validate($entity, $parameters);

            $formType = $this->mapEntityToForm($this->entityClass);

            $form = $this->formFactory->create($formType, $entity, ['method' => $method]);

            /**
             * @reference: http://symfony.com/doc/current/form/direct_submit.html
             *           docs say this is required, but wtf?
             *
             *           $form->handleRequest($request);
             *
             */

            $form->submit($parameters, ($method == 'PATCH' ? false : true));

            if ($form->isValid()) {

                $entity = $form->getData();

                switch($method) {

                    case 'POST':
                        $this->auth->isGranted('create', $entity);
                        break;

                    case 'PUT':
                        $this->auth->isGranted('edit', $entity);
                        break;

                    case 'PATCH':
                        $this->auth->isGranted('patch', $entity);
                        break;

                    case 'DELETE':
                        $this->auth->isGranted('delete', $entity);
                        break;

                }

                $this->om->persist($entity);
                $this->om->flush();

                return $entity;

            }
            else {
                $formErrors = new FormErrors();
                $errors = $formErrors->getArray($form);
                $exception = $formErrors->getErrorsAsString($errors);
                throw new InvalidFormException($exception, get_class($form));
            }

        }
        catch(NotNullConstraintViolationException $e) {
            throw $e;
        }
        catch(TransformationFailedException $e) {
            throw $e;
        }
        catch(ContextErrorException $e) {
            throw $e;
        }
        catch(UnexpectedTypeException $e) {
            throw $e;
        }
        catch(InvalidPropertyPathException $e) {
            throw $e;
        }
        catch(InvalidFormException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Fail safe validation method for things we cannot (or should not) do in the validation YAML.
     *
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @internal param $data
     */
    public function validate(EntityInterface $entity, array $parameters = array())
    {
        // do something clever here..
    }

    /**
     * Returns a new object based on the entity parameter of constructor
     *
     * @return EntityInterface
     */
    public function createEntity()
    {
        return new $this->entityClass();
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function mapEntityToForm($entity)
    {

        $map = array(
            'Tavro\Entity\Account'                    => 'Tavro\Form\AccountType',
            'Tavro\Entity\AccountType'                => 'Tavro\Form\AccountTypeType',
            'Tavro\Entity\AccountChargify'            => 'Tavro\Form\AccountChargifyType',
            'Tavro\Entity\AccountUser'                => 'Tavro\Form\AccountUserType',
            'Tavro\Entity\AccountGroup'               => 'Tavro\Form\AccountGroupType',
            'Tavro\Entity\AccountGroupUser'           => 'Tavro\Form\AccountGroupUserType',
            'Tavro\Entity\OrganizationComment'        => 'Tavro\Form\OrganizationCommentType',
            'Tavro\Entity\Comment'                    => 'Tavro\Form\CommentType',
            'Tavro\Entity\Contact'                    => 'Tavro\Form\ContactType',
            'Tavro\Entity\ExpenseCategory'            => 'Tavro\Form\ExpenseCategoryType',
            'Tavro\Entity\ExpenseComment'             => 'Tavro\Form\ExpenseCommentType',
            'Tavro\Entity\ExpenseTag'                 => 'Tavro\Form\ExpenseTagType',
            'Tavro\Entity\Expense'                    => 'Tavro\Form\ExpenseType',
            'Tavro\Entity\FundingRoundComment'        => 'Tavro\Form\FundingRoundCommentType',
            'Tavro\Entity\FundingRound'               => 'Tavro\Form\FundingRoundType',
            'Tavro\Entity\Image'                      => 'Tavro\Form\ImageType',
            'Tavro\Entity\NodeComment'                => 'Tavro\Form\NodeCommentType',
            'Tavro\Entity\NodeTag'                    => 'Tavro\Form\NodeTagType',
            'Tavro\Entity\Node'                       => 'Tavro\Form\NodeType',
            'Tavro\Entity\Organization'               => 'Tavro\Form\OrganizationType',
            'Tavro\Entity\Person'                     => 'Tavro\Form\PersonType',
            'Tavro\Entity\ProductCategory'            => 'Tavro\Form\ProductCategoryType',
            'Tavro\Entity\Product'                    => 'Tavro\Form\ProductType',
            'Tavro\Entity\RevenueCategory'            => 'Tavro\Form\RevenueCategoryType',
            'Tavro\Entity\RevenueComment'             => 'Tavro\Form\RevenueCommentType',
            'Tavro\Entity\RevenueProduct'             => 'Tavro\Form\RevenueProductType',
            'Tavro\Entity\RevenueService'             => 'Tavro\Form\RevenueServiceType',
            'Tavro\Entity\Revenue'                    => 'Tavro\Form\RevenueType',
            'Tavro\Entity\Role'                       => 'Tavro\Form\RoleType',
            'Tavro\Entity\ServiceCategory'            => 'Tavro\Form\ServiceCategoryType',
            'Tavro\Entity\Service'                    => 'Tavro\Form\ServiceType',
            'Tavro\Entity\Shareholder'                => 'Tavro\Form\ShareholderType',
            'Tavro\Entity\Syndicate'                  => 'Tavro\Form\SyndicateType',
            'Tavro\Entity\Tag'                        => 'Tavro\Form\TagType',
            'Tavro\Entity\User'                       => 'Tavro\Form\UserType',
            'Tavro\Entity\Variable'                   => 'Tavro\Form\VariableType',
        );

        if (array_key_exists($entity, $map)) {
            return $map[$entity];
        }

        $message = sprintf('%s is not a valid entity.', str_replace('Tavro\Entity', '', $entity));
        throw new ApiException($message);

    }

    /**
     * Error handler for Symfony Forms.
     *
     * @param \Symfony\Component\Form\Form $form
     *
     * @return array
     */
    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }

    /**
     * @param array $parameters
     *
     * @return array
     * @throws \Exception
     */
    public function typeahead(array $parameters)
    {
        try {

            $entities = $this->getRepository()->getAll();

            $items = array();

            foreach($entities as $entity) {
                if($this->auth->isGranted('view', $entity)) {
                    $items[] = $entity;
                }
            }

            return $items;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}
