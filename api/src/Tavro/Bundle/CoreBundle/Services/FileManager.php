<?php

namespace Tavro\Bundle\CoreBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Model\S3EntityInterface;

use Doctrine\ORM\EntityManager;

use Gaufrette\Filesystem;
use Gaufrette\Adapter\AwsS3;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\Credentials;

/**
 * Class FileManager
 *
 * @package Tavro\Bundle\CoreBundle\Services
 */
class FileManager implements ContainerAwareInterface
{
    private $container;
    private $s3;

    protected $aws_access_key_id;
    protected $aws_secret_access_key;
    protected $aws_s3_region;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * FileManager constructor.
     *
     * @param $aws_access_key_id
     * @param $aws_secret_access_key
     * @param $aws_s3_region
     */
    public function __construct($aws_access_key_id, $aws_secret_access_key, $aws_s3_region)
    {
        $credentials = new Credentials(
            $aws_access_key_id,
            $aws_secret_access_key
        );

        // Instantiate the S3 client with your AWS credentials
        $s3 = S3Client::factory(array(
            'credentials' => $credentials,
            'version' => 'latest',
            'region' => $aws_s3_region
        ));

        $this->s3 = $s3;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param \Gaufrette\Adapter\AwsS3 $adapter
     *
     * @return mixed
     * @throws \Exception
     */
    public function upload(UploadedFile $file, AwsS3 $adapter)
    {
        try {

            $filename = sprintf('%s.%s', uniqid(), $file->getClientOriginalExtension());
            $adapter->setMetadata('Content-Type', $file->getMimeType());
            $response = $adapter->write($filename, file_get_contents($file->getPathname()));
            return $filename;

        }
        catch(S3Exception $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $data
     * @param $filename
     * @param \Gaufrette\Adapter\AwsS3 $adapter
     *
     * @return mixed
     * @throws \Exception
     */
    public function move(array $data, $filename, AwsS3 $adapter)
    {
        try {
            $adapter->setMetadata('Content-Type', $data['ContentType']);
            $response = $adapter->write($filename, $data['Body']);
            return $filename;
        }
        catch(S3Exception $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }


    /**
     * Physically delete a file from S3.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\S3EntityInterface $entity
     *
     * @throws \Exception
     */
    public function delete(S3EntityInterface $entity)
    {
        try {

            return $this->s3->deleteObject(array(
                'Bucket' => $entity->getBucket(),
                'Key'    => $entity->getAwsKey()
            ));

        }
        catch(S3Exception $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all files in a bucket.
     *
     * @param $bucket
     *
     * @return \ArrayIterator|\Iterator
     * @throws \Exception
     */
    public function getBucketFiles($bucket)
    {
        try {
            // Use the high-level iterators (returns ALL of your objects).
            $objects = $this->s3->getIterator('ListObjects', array('Bucket' => $bucket));
            return $objects;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Fix the ContentType Metadata for an array of S3 objects.
     *
     * @param $bucket
     *
     * @throws \Exception
     */
    public function fixMetadata($bucket)
    {
        try {

            $objects = $this->getBucketFiles($bucket);
            foreach($objects as $object) {
                $key = $object['Key'];
                $this->setMimeType($bucket, $key);
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the mime type of a file based on its extension.
     * Admittedly not a beautiful solution, but it fixes Assetic/S3 defaulting
     * to octet/stream which breaks most things.
     *
     * @param $bucket
     * @param $key
     *
     * @return mixed
     * @throws \Exception
     */
    public function setMimeType($bucket, $key)
    {
        try {

            $fileParts = pathinfo($key);

            if(!isset($fileParts['extension'])) {
                return;
            }

            switch($fileParts['extension'])
            {
                case 'jpg':
                    $mimetype = 'image/jpeg';
                    break;

                case 'jpeg':
                    $mimetype = 'image/jpeg';
                    break;

                case 'png':
                    $mimetype = 'image/png';
                    break;

                case 'gif':
                    $mimetype = 'image/gif';
                    break;

                case 'tif':
                    $mimetype = 'image/tiff';
                    break;

                case 'tiff':
                    $mimetype = 'image/tiff';
                    break;

                case 'bmp':
                    $mimetype = 'image/bmp';
                    break;

                case 'zip':
                    $mimetype = 'application/zip';
                    break;

                case 'pdf':
                    $mimetype = 'application/pdf';
                    break;

                case 'css':
                    $mimetype = 'text/css';
                    break;

                case 'js':
                    $mimetype = 'text/javascript';
                    break;

                case 'yml':
                    $mimetype = 'text/plain';
                    break;

                default:
                    return;
                    break;

            }

            $source = sprintf('%s/%s/%s',
                $bucket,
                $fileParts['dirname'],
                $fileParts['basename']
            );

            $response = $this->s3->copyObject(array(
                'Bucket' => $bucket,
                'Key' => $key,
                'CopySource' => urlencode($source),
                'MetadataDirective' => 'REPLACE',
                'CacheControl' => 'max-age=31536000',
                'Expires' => gmdate('D, d M Y H:i:s T', strtotime('+1 week')),
                'ContentType' => $mimetype,
            ));

            return $response;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }
    
}