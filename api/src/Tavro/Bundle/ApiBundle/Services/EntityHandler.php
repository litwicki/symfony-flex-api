<?php

namespace Tavro\Bundle\ApiBundle\Services;

use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\ApiEntityInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;

use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\ApiBundle\Model\HandlerInterface;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestLimitException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestSizeException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class EntityHandler implements HandlerInterface
{
    public $om;
    public $entityClass;
    public $repository;
    public $formFactory;
    public $encoder;
    public $tokenStorage;
    public $auth;
    public $pageSize;
    public $statusActive;
    public $statusPending;
    public $staticDisabled;
    public $s3;
    public $amazon_s3_url;
    public $isModerator = false;
    public $isAdmin = false;
    public $user = false;

    public function __construct(Container $container, ObjectManager $om, $entityClass)
    {
        $this->container = $container;
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $container->get('form.factory');
        $this->encoder = $container->get('security.encoder_factory');
        $this->tokenStorage = $container->get('security.token_storage');
        $this->auth = $container->get('security.authorization_checker');

        $this->pageSize = $this->container->getParameter('page_size');
        $this->statusPending = $this->container->getparameter('status_pending');
        $this->statusDisabled = $this->container->getparameter('status_disabled');
        $this->statusActive = $this->container->getparameter('status_active');
        $this->amazon_s3_url = $this->container->getParameter('amazon_s3_url');

        $token = $this->tokenStorage->getToken();

        if(!is_null($token)) {
            $this->user = $token->getUser();
            $this->isModerator = $this->auth->isGranted('ROLE_MODERATOR');
            $this->isAdmin = $this->auth->isGranted('ROLE_ADMIN');
        }
    }

    /**
     * Get a Entity.
     *
     * @param mixed $id
     *
     * @return EntityInterface
     */
    public function find($id)
    {
        try {
            $entity = $this->repository->find($id);
            if($this->auth->isGranted('view', $entity)) {
                return $entity;
            }
            else {
                $message = sprintf('You are not authorized to view %s %s.', $this->entityClass, $id);
                throw new ApiAccessDeniedException($message);
            }
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
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
            $size = isset($params['size']) ? $params['size'] : $this->pageSize;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'id';

            $sortOrder = array($orderBy => $sort);

            if(!isset($params['status'])) {
                $params['status'] = $this->statusActive; //@TODO: Make this a constant fetched from Model\Entity.php
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
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
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|mixed
     * @throws \Exception
     */
    public function create(Request $request, array $parameters)
    {
        try {

            $entity = $this->createEntity();
            $this->validate($entity, $parameters);
            $entity = $this->processForm($request, $entity, $parameters, 'POST');

            /**
             * If this is an ApiEntity immediately save so the slug property
             * is updated correctly with the entity Id: {id}-{url-save-title}
             */
            if($entity instanceof ApiEntityInterface) {
                return $this->put($entity, $parameters);
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
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Edit an Interface.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     */
    public function put(Request $request, EntityInterface $entity, array $parameters)
    {
        try {
            return $this->processForm($request, $entity, $parameters, 'PUT');
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function patch(Request $request, EntityInterface $entity, array $parameters)
    {
        try {

            if(!$this->auth->isGranted('patch', $entity)) {
                $message = sprintf('Unable to properly "patch" %s: %s', get_class($entity), $entity->__toString());
                throw new ApiAccessDeniedException($message);
            }

            return $this->applyPatch($request, $entity, $parameters);

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
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     * @internal param $Request
     */
    public function applyPatch(Request $request, EntityInterface $entity, array $parameters)
    {
        try {
            $entity = $this->processForm($request, $entity, $parameters, 'PATCH');
            return $entity;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Process the form submission through the specified FormType validation process.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     * @param string $method
     *
     * @throws \Exception
     * @throws \Symfony\Component\Debug\Exception\ContextErrorException
     */
    public function processForm(Request $request, EntityInterface $entity, array $parameters, $method = 'PUT')
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

            $array = array(
                'Tavro\Bundle\CoreBundle\Entity\User'         => 'Tavro\Bundle\CoreBundle\Form\UserType',
                'Tavro\Bundle\CoreBundle\Entity\Image'        => 'image_type',
                'Tavro\Bundle\CoreBundle\Entity\File'         => 'file_type',
                'Tavro\Bundle\CoreBundle\Entity\Node'         => 'node_type',
                'Tavro\Bundle\CoreBundle\Entity\Comment'      => 'comment_type',
                'Tavro\Bundle\CoreBundle\Entity\Role'         => 'role_type',
                'Tavro\Bundle\CoreBundle\Entity\Variable'     => 'variable_type',
                'Tavro\Bundle\CoreBundle\Entity\NodeTag'      => 'node_tag_type',
                'Tavro\Bundle\CoreBundle\Entity\Tag'          => 'tag_type',
            );

            if (array_key_exists($entity, $array)) {
                return $array[$entity];
            }

        }
        catch(\Exception $e) {
            throw new ApiException(sprintf('Invalid entity type data %s', $entity));
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
     * Based on the directory, build the adapter file system.
     *
     * @param $directory
     *
     * @return mixed
     * @throws \Exception
     */
    public function getAdapter($directory)
    {
        try {

            switch($directory) {

                case 'avatars':
                    $adapterName    = 'avatars';
                    $alias          = 'avatars_filesystem';
                    break;

                case 'tavro';
                    $adapterName    = 'tavro_images';
                    $alias          = 'tavro_images_filesystem';
                    break;

                case 'mods';
                    $adapterName    = 'mod_images';
                    $alias          = 'mod_images_filesystem';
                    break;

                default:
                    $adapterName    = 'images';
                    $alias          = 'images_filesystem';
                    break;

            }

            $adapter = $this->container->get($alias)->getAdapter($adapterName);
            return $adapter;

        }
        catch(\Exception $e) {
            throw $e;
        }
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
