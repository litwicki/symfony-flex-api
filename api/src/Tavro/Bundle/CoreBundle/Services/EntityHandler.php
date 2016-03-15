<?php

namespace Tavro\Bundle\CoreBundle\Services;

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
use Tavro\Bundle\CoreBundle\Services\EntityHandlerInterface;

class EntityHandler implements EntityHandlerInterface
{
    public $om;
    public $entityClass;
    public $repository;
    public $formFactory;
    public $encoder;
    public $tokenStorage;
    public $auth;
    public $validator;

    public $s3;
    public $amazon_s3_url;

    protected $isAdmin = false;
    protected $user = false;

    const PAGE_SIZE = 25;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;
    const STATUS_DISABLED = 0;

    const ACCESS_DENIED_MESSAGE = 'You are not authorized to perform this action!';

    public function __construct(ObjectManager $om, FormFactory $formFactory, Validator $validator, EncoderFactory $encoder, TokenStorage $tokenStorage, AuthorizationChecker $auth, $amazon_s3_url, $entityClass)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);

        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
        $this->auth = $auth;

        $this->amazon_s3_url = $amazon_s3_url;

        $token = $this->tokenStorage->getToken();

        if(!is_null($token)) {
            $this->user = $token->getUser();
            $this->isAdmin = $this->auth->isGranted('ROLE_ADMIN');
        }
    }

    /**
     * Get an array of all Organizations this User should have access to.
     * This includes Organizations they "own" as well as ones they are mere Users of.
     *
     */
    public function getMyOrganizations()
    {
        try {

            $organizations = array();

            foreach($this->user->getOrganizations() as $entity) {
                $organizations[$entity->getId()] = $entity;
            }

            foreach($this->user->getUserOrganizations() as $entity) {
                $organizations[$entity->getOrganization()->getId()] = $entity->getOrganization();
            }

            return $organizations;

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param mixed $id
     *
     * @return object
     * @throws \Exception
     */
    public function find($id)
    {
        try {
            $entity = $this->repository->find($id);
            if($this->auth->isGranted('view', $entity)) {
                return $entity;
            }
            else {
                $message = sprintf('You are not authorized to this view %s.', $this->entityClass, $id);
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
                $entities = $this->findAll();
            }
            else {
                foreach ($ids as $id) {
                    $entities[] = $this->repository->find($id);
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
     * @return array|void
     */
    public function findAll(array $params = null)
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

            $entities = $this->repository->findBy(
                $params,
                $sortOrder,
                $size,
                $offset
            );

            $items = array();

            foreach($entities as $entity) {
                if($this->auth->isGranted('view', $entity)) {
                    $items[] = $entity;
                }
            }

            return array(
                'data' => $items,
                'message' => sprintf('%s %s retrieved.',
                    count($items),
                    str_replace('Tavro\\Bundle\\CoreBundle\\Entity\\', '', Inflector::pluralize($this->entityClass))
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
     * Submit a new Entity.
     *
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     * @throws \Exception
     */
    public function post(array $parameters)
    {
        try {
            return $this->create($parameters);
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
     * @param array $parameters
     *
     * @return object|\Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     * @throws \Exception
     */
    public function create(array $parameters)
    {
        try {

            if(!isset($parameters['status'])) {
                $parameters['status'] = $this::STATUS_ACTIVE;
            }

            $entity = $this->createEntity();
            $this->validate($entity, $parameters);
            $entity = $this->processForm($entity, $parameters, 'POST');

            /**
             * If this is an ApiEntity immediately save so the slug property
             * is updated correctly with the entity Id: {id}-{url-save-title}
             */
            if($entity instanceof EntityInterface) {
                return $this->put($entity, $parameters);
            }

            return $entity;

        }
        catch(ApiAccessDeniedException $e) {
            throw new ApiAccessDeniedException($this::ACCESS_DENIED_MESSAGE);
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
     * Edit an Interface.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     */
    public function put(EntityInterface $entity, array $parameters)
    {
        try {
            return $this->processForm($entity, $parameters, 'PUT');
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     */
    public function delete(EntityInterface $entity)
    {
        try {

            if(!($this->auth->isGranted('delete', $entity))) {
                $message = sprintf('You are not authorized to delete %s!', $entity->__toString());
                throw new ApiAccessDeniedException($message);
            }

            $this->om->remove($entity);
            $this->om->flush();

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Removing an entity does not physically delete it, but "archives" it.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     */
    public function remove(EntityInterface $entity)
    {
        try {
            $entity->setStatus(0);
            $this->om->persist($entity);
            $this->om->flush();
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function patch(EntityInterface $entity, array $parameters)
    {
        try {

            if(!$this->auth->isGranted('patch', $entity)) {
                $message = sprintf('Unable to properly "patch" %s: %s', get_class($entity), $entity->__toString());
                throw new ApiAccessDeniedException($message);
            }

            return $this->applyPatch($entity, $parameters);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Separate the actual application of the patch parameters, so we can override
     * in individual entities without replicating this code repeatedly.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     * @internal param $Request
     */
    public function applyPatch(EntityInterface $entity, array $parameters)
    {
        try {
            $entity = $this->processForm($entity, $parameters, 'PATCH');
            return $entity;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Process the form submission through the specified FormType validation process.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     * @param string $method
     *
     * @throws \Exception
     * @throws \Symfony\Component\Debug\Exception\ContextErrorException
     */
    public function processForm(EntityInterface $entity, array $parameters, $method = 'PUT')
    {
        try {

            $this->validate($entity, $parameters, $method);

            $formType = $this->mapEntityToForm($this->entityClass);

            $form = $this->formFactory->create($formType, $entity, array('method' => $method));

            $form->submit($parameters, ($method == 'PATCH' ? false : true));

            if ($form->isValid()) {

                $entity = $form->getData();
                $class = new \ReflectionClass($entity);

                switch($method) {

                    case 'POST':
                        if(!($this->auth->isGranted('create', $entity))) {
                            $message = sprintf('You are not authorized to create a new %s.', $class->getShortName());
                            throw new ApiAccessDeniedException($message);
                        }
                        break;

                    case 'PUT':
                        if(!($this->auth->isGranted('edit', $entity))) {
                            $message = sprintf('You are not authorized to edit %s "%s"', $class->getShortName(), $entity->__toString());
                            throw new ApiAccessDeniedException($message);
                        }
                        break;

                }

                $this->om->persist($entity);
                $this->om->flush();

                return $entity;
            }
            else {
                /**
                 * @TODO: properly clean this up so it reports a usable error message
                 *      without using the deprecated function(s)
                 */
                $errors = (string) $form->getErrors(true, false);
                //$errors = $form->getErrorsAsString();
                throw new InvalidFormException($errors);
            }

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
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     */
    public function validate(EntityInterface $entity, array $parameters)
    {
        /**
         * @placeholder
         */
    }

    /**
     * Returns a new object based on the entity parameter of constructor
     *
     * @return object
     */
    public function createEntity()
    {
        return new $this->entityClass();
    }

    /**
     * @param $entity
     * @return
     * @throws ApiException
     */
    public function mapEntityToForm($entity)
    {
        try {

            $map = array(
                'Tavro\Bundle\CoreBundle\Entity\Comment'             => 'Tavro\Bundle\CoreBundle\Form\CommentType',
                'Tavro\Bundle\CoreBundle\Entity\CustomerComment'     => 'Tavro\Bundle\CoreBundle\Form\CustomerCommentType',
                'Tavro\Bundle\CoreBundle\Entity\Customer'            => 'Tavro\Bundle\CoreBundle\Form\CustomerType',
                'Tavro\Bundle\CoreBundle\Entity\ExpenseCategory'     => 'Tavro\Bundle\CoreBundle\Form\ExpenseCategoryType',
                'Tavro\Bundle\CoreBundle\Entity\ExpenseComment'      => 'Tavro\Bundle\CoreBundle\Form\ExpenseCommentType',
                'Tavro\Bundle\CoreBundle\Entity\ExpenseTag'          => 'Tavro\Bundle\CoreBundle\Form\ExpenseTagType',
                'Tavro\Bundle\CoreBundle\Entity\Expense'             => 'Tavro\Bundle\CoreBundle\Form\ExpenseType',
                'Tavro\Bundle\CoreBundle\Entity\FundingRoundComment' => 'Tavro\Bundle\CoreBundle\Form\FundingRoundCommentType',
                'Tavro\Bundle\CoreBundle\Entity\FundingRound'        => 'Tavro\Bundle\CoreBundle\Form\FundingRoundType',
                'Tavro\Bundle\CoreBundle\Entity\Image'               => 'Tavro\Bundle\CoreBundle\Form\ImageType',
                'Tavro\Bundle\CoreBundle\Entity\NodeComment'         => 'Tavro\Bundle\CoreBundle\Form\NodeCommentType',
                'Tavro\Bundle\CoreBundle\Entity\NodeTag'             => 'Tavro\Bundle\CoreBundle\Form\NodeTagType',
                'Tavro\Bundle\CoreBundle\Entity\Node'                => 'Tavro\Bundle\CoreBundle\Form\NodeType',
                'Tavro\Bundle\CoreBundle\Entity\OrganizationType'    => 'Tavro\Bundle\CoreBundle\Form\OrganizationType',
                'Tavro\Bundle\CoreBundle\Entity\ProductCategory'     => 'Tavro\Bundle\CoreBundle\Form\ProductCategoryType',
                'Tavro\Bundle\CoreBundle\Entity\Product'             => 'Tavro\Bundle\CoreBundle\Form\ProductType',
                'Tavro\Bundle\CoreBundle\Entity\RevenueCategory'     => 'Tavro\Bundle\CoreBundle\Form\RevenueCategoryType',
                'Tavro\Bundle\CoreBundle\Entity\RevenueComment'      => 'Tavro\Bundle\CoreBundle\Form\RevenueCommentType',
                'Tavro\Bundle\CoreBundle\Entity\RevenueProduct'      => 'Tavro\Bundle\CoreBundle\Form\RevenueProductType',
                'Tavro\Bundle\CoreBundle\Entity\RevenueService'      => 'Tavro\Bundle\CoreBundle\Form\RevenueServiceType',
                'Tavro\Bundle\CoreBundle\Entity\Revenue'             => 'Tavro\Bundle\CoreBundle\Form\RevenueType',
                'Tavro\Bundle\CoreBundle\Entity\Role'                => 'Tavro\Bundle\CoreBundle\Form\RoleType',
                'Tavro\Bundle\CoreBundle\Entity\ServiceCategory'     => 'Tavro\Bundle\CoreBundle\Form\ServiceCategoryType',
                'Tavro\Bundle\CoreBundle\Entity\Service'             => 'Tavro\Bundle\CoreBundle\Form\ServiceType',
                'Tavro\Bundle\CoreBundle\Entity\Shareholder'         => 'Tavro\Bundle\CoreBundle\Form\ShareholderType',
                'Tavro\Bundle\CoreBundle\Entity\UserOrganization'    => 'Tavro\Bundle\CoreBundle\Form\UserOrganizationTyp',
                'Tavro\Bundle\CoreBundle\Entity\User'                => 'Tavro\Bundle\CoreBundle\Form\UserType',
                'Tavro\Bundle\CoreBundle\Entity\Variable'            => 'Tavro\Bundle\CoreBundle\Form\VariableType',
            );

            if (array_key_exists($entity, $map)) {
                return $map[$entity];
            }

        }
        catch(\Exception $e) {
            $message = sprintf('%s is not a valid entity type', $entity);
            throw new ApiException($message);
        }

    }

    /**
     * @param $entity
     * @param array $ids
     * @param $propertyName
     */
    public function processEntities($entity, array $ids, $propertyName)
    {
        try {

            if(!empty($ids)) {

                $class = Inflector::singularize($propertyName);
                $class = Inflector::classify($class);

                /**
                 * Hackification to fix our TavroClass referred to as $classes
                 * in various places, to avoid silly confusion...
                 */
                if($class === 'Class') {
                    $class = 'TavroClass';
                }

                $repositoryName = sprintf('TavroCoreBundle:%s', $class);

                $getAll = sprintf('get%s', ucwords(str_replace('_','',$propertyName)));
                $setter = sprintf('add%s', $class);
                $remover = sprintf('remove%s', $class);

                $entities = $entity->$getAll();
                $selected = array();

                if(!empty($entities)) {

                    foreach($entities as $item) {

                        $thisId = $item->getId();

                        /**
                         * Check if a currently assigned entity is *not* in $ids.
                         * If this is the case, we remove the entity association from $this.
                         */
                        if(!in_array($thisId, $ids)) {
                            $this->$remover($entity);
                        }

                        $selected[] = $thisId;

                    }

                }

                /**
                 * Process every id in $ids and if it is not currently associated,
                 * we want to add it. First find it by Id, then add as necessary.
                 */
                foreach($ids as $id) {

                    if(!in_array($id, $selected)) {
                        $child = $this->om->getRepository($repositoryName)->find($id);
                        $this->$setter($child);
                    }

                }

                $this->om->flush();

            }

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(ApiException $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\Form\Form $form
     *
     * @return array
     */
    public function getFormErrors(Form $form)
    {

        $errors = array();

        foreach($form->getErrors() as $error) {

            $errors[] = $error->getMessage();

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

            $entities = $this->repository->findAll();

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
