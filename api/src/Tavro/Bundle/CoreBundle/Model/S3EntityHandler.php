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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

class S3EntityHandler extends EntityHandler implements S3EntityHandlerInterface
{

    protected $bucket;

    public function __construct(ObjectManager $om, FormFactory $formFactory, Validator $validator, EncoderFactory $encoderFactory, TokenStorage $tokenStorage, AuthorizationChecker $auth, $amazon_s3_url, $entityClass, $bucket)
    {
        parent::__construct();
        $this->bucket = $bucket;
    }

    /**
     * Build the fully qualified URL to the image.
     *
     * @param $filename
     * @param $directory
     */
    public function buildUrl($filename, $directory)
    {
        if($directory == '') {

            $url = sprintf('%s/%s/%s',
                $this->container->getParameter('amazon_s3_url'),
                $this->getBucket(),
                $filename
            );

        }
        else {

            $url = sprintf('%s/%s/%s',
                $this->container->getParameter('amazon_s3_url'),
                $this->getBucket(),
                $directory,
                $filename
            );

        }
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

                default:
                    $adapterName    = 'tavro';
                    $alias          = 'tavro_filesystem';
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
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string $directory
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|mixed|\Symfony\Component\HttpFoundation\File\UploadedFile
     * @throws \Exception
     */
    public function upload(UploadedFile $file, $directory = '')
    {
        try {

            $uploader = $this->container->get('tavro.file_manager');
            $adapter = $this->getAdapter($directory);
            $key = $uploader->upload($file, $adapter);
            $url = $adapter->getUrl($key);

            $params = array(
                'aws_url'               => $url,
                'aws_key'               => $key,
                'bucket'                => $this->getBucket(),
                'filesize'              => $file->getClientSize(),
                'mime_type'             => $file->getMimeType(),
                'original_filename'     => $file->getClientOriginalName()
            );

            $file = $this->create($params);

            return $file;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $data
     * @param $filename
     * @param string $directory
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|mixed
     * @throws \Exception
     */
    public function move(array $data, $filename, $directory = '')
    {
        try {

            $uploader = $this->container->get('tavro.file_manager');
            $adapter = $this->getAdapter($directory);

            $key = $uploader->move(
                array(
                    'Bucket'        => $this->getBucket(),
                    'Key'           => $data['Key'],
                    'Body'          => $data['Body'],
                    'ContentType'   => $data['ContentType'],
                    'ContentLength' => $data['ContentLength'],
                    'ACL'           => $data['ACL']
                ),
                $filename,
                $adapter
            );

            /**
             * Build the full URL for this Image.
             */
            $url = $adapter->getUrl($key);

            $params = array(
                'aws_url'               => $url,
                'aws_key'               => $key,
                'bucket'                => $this->getBucket(),
                'filesize'              => $data['params']['filesize'],
                'mime_type'             => $data['params']['mime_type'],
                'original_filename'     => $data['params']['original_filename']
            );

            $file = $this->create($params);

            return $file;

        }
        catch(\Exception $e) {
            throw $e;
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
                throw new ApiAccessDeniedException($entity->getId());
            }

            $this->om->remove($entity);
            $this->om->flush();

            $this->container->get('tavro.file_manager')->delete($entity);

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

}