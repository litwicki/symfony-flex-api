<?php

namespace Tavro\Bundle\CoreBundle\Handler;

use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Services\Api\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class FileHandler extends EntityHandler
{
    /**
     * @return mixed
     */
    public function getBucket()
    {
        return $this->container->getParameter('file_bucket');
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