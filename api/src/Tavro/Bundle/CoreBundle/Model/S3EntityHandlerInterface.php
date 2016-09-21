<?php

namespace Tavro\Bundle\CoreBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * Interface S3EntityHandlerInterface
 *
 * @package Tavro\Bundle\CoreBundle\Model
 */
interface S3EntityHandlerInterface
{

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     */
    public function delete(EntityInterface $entity);

    /**
     * @param array $data
     * @param $filename
     * @param string $directory
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|mixed
     * @throws \Exception
     */
    public function move(array $data, $filename, $directory = '');

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string $directory
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function upload(UploadedFile $file, $directory = '');
}
