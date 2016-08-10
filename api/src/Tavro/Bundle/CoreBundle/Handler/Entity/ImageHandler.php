<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Gaufrette\Adapter\AwsS3;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Model\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ImageHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class ImageHandler extends EntityHandler
{
    /**
     * @return mixed
     */
    public function getBucket()
    {
        return $this->container->getParameter('image_bucket');
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @param string $directory
     *
     * @return mixed|\Symfony\Component\HttpFoundation\File\UploadedFile|\Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function upload(Request $request, UploadedFile $image, $directory = '')
    {
        try {

            $uploader = $this->container->get('tavro.file_manager');
            $adapter = $this->getAdapter($directory);
            $key = $uploader->upload($image, $adapter);
            list($width, $height, $type, $attr) = getimagesize($image->getPathname());

            $url = $adapter->getUrl($key);

            $params = array(
                'aws_url'               => $url,
                'aws_key'               => $key,
                'directory'             => $directory,
                'bucket'                => $this->getBucket(),
                'filesize'              => $image->getClientSize(),
                'mime_type'             => $image->getMimeType(),
                'original_filename'     => $image->getClientOriginalName(),
                'height'                => $height,
                'width'                 => $width,
            );

            $image = $this->create($request, $params);

            return $image;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     * @param $filename
     * @param string $directory
     *
     * @return mixed|\Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function move(Request $request, array $data, $filename, $directory = '')
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
                'directory'             => $directory,
                'bucket'                => $this->getBucket(),
                'filesize'              => $data['params']['filesize'],
                'mime_type'             => $data['params']['mime_type'],
                'original_filename'     => $data['params']['original_filename'],
                'height'                => $data['params']['height'],
                'width'                 => $data['params']['width'],
            );

            $image = $this->create($request, $params);

            return $image;

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

            /**
             * Find and remove any associated ModImage
             */
            $modImages = $this->container->get('doctrine')->getManager()->getRepository('TavroCoreBundle:ModImage')->findBy(array(
                'image' => $entity
            ));

            foreach($modImages as $modImage) {
                $this->om->remove($modImage);
            }

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

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     * @param array|string $method
     *
     * @return mixed|\Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function processForm(EntityInterface $entity, array $parameters, $method = self::HTTP_METHOD_POST)
    {
        try {

            $formType = $this->mapEntityToForm($this->entityClass);

            $form = $this->formFactory->create($formType, $entity, array('method' => $method));

            $form->submit($parameters);

            if ($form->isValid()) {

                $entity = $form->getData();
                //$entity = $this->updateCache($entity);

                $this->om->persist($entity);
                $this->om->flush($entity);

                return $entity;
            }
            else {
                $errors = (string) $form->getErrors(true, false);
                throw new ApiException($errors);
            }

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Image $image
     * @param \Tavro\Bundle\CoreBundle\Entity\Mod $mod
     *
     * @throws \Exception
     */
    public function addMod(Image $image, Mod $mod)
    {
        try {

            $mi = new ModImage();
            $mi->setImage($image);
            $mi->setMod($mod);
            $this->om->persist($mi);
            $this->om->flush();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}