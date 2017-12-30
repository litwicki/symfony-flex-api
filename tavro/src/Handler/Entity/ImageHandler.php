<?php

namespace Tavro\Handler\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Tavro\Exception\Api\ApiException;
use Tavro\Handler\S3EntityHandler;
use Tavro\Model\HandlerInterface\S3EntityHandlerInterface;
use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Exception\Api\ApiAccessDeniedException;

use Gaufrette\Adapter\AwsS3;

/**
 * Class ImageHandler
 *
 * @package Tavro\Handler\Entity
 */
class ImageHandler extends S3EntityHandler implements S3EntityHandlerInterface
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @param string $directory
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
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
     * @param string $filename
     * @param string $directory
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Model\EntityInterface\EntityInterface $entity
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