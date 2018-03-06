<?php

namespace App\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Validator\RecursiveValidator as Validator;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Model\HandlerInterface\S3EntityHandlerInterface;

class S3EntityHandler extends EntityHandler implements S3EntityHandlerInterface
{
    protected $bucket;

    /**
     * S3EntityHandler constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     * @param \Symfony\Component\Form\FormFactory $formFactory
     * @param \Symfony\Component\Validator\Validator\RecursiveValidator $validator
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactory $encoderFactory
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $tokenStorage
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationChecker $auth
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param $entityClass
     * @param $bucket
     */
    public function __construct(ObjectManager $om, FormFactory $formFactory, Validator $validator, EncoderFactory $encoderFactory, TokenStorage $tokenStorage, AuthorizationChecker $auth, EventDispatcherInterface $dispatcher, $entityClass, $bucket, $amazon_s3_url)
    {
        parent::__construct($om, $formFactory, $validator, $encoderFactory, $tokenStorage, $auth, $dispatcher, $entityClass);
        $this->bucket = $bucket;
        $this->amazon_s3_url = $amazon_s3_url;
    }

    /**
     * @TODO: update this with some business checks.
     * 
     * @return mixed
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * Build the fully qualified URL to the image.
     *
     * @param $docname
     * @param $directory
     *
     * @return string
     */
    public function buildUrl($docname, $directory)
    {
        if($directory == '') {

            $url = sprintf('%s/%s/%s',
                $this->amazon_s3_url,
                $this->getBucket(),
                $docname
            );

        }
        else {

            $url = sprintf('%s/%s/%s',
                $this->amazon_s3_url,
                $this->getBucket(),
                $directory,
                $docname
            );

        }

        return $url;
    }

    /**
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $doc
     * @param string $directory
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function upload(Request $request, UploadedFile $doc, $directory = '')
    {
        try {

            $uploader = $this->container->get('tavro.file_manager');
            $adapter = $this->getAdapter($directory);
            $key = $uploader->upload($doc, $adapter);
            $url = $adapter->getUrl($key);

            $params = array(
                'aws_url'               => $url,
                'aws_key'               => $key,
                'bucket'                => $this->getBucket(),
                'filesize'              => $doc->getClientSize(),
                'mime_type'             => $doc->getMimeType(),
                'original_filename'     => $doc->getClientOriginalName()
            );

            $doc = $this->create($request, $params);

            return $doc;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     * @param $docname
     * @param string $directory
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function move(Request $request, array $data, $docname, $directory = '')
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
                $docname,
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

            $doc = $this->create($request, $params);

            return $doc;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     *
     * @return void
     */
    public function remove(Request $request, EntityInterface $entity)
    {
        try {

            if(!($this->auth->isGranted('delete', $entity))) {
                throw new ApiAccessDeniedException($entity->getId());
            }

            $this->delete($request, $entity);

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