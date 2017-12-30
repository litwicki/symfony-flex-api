<?php

namespace Tavro\Model\HandlerInterface;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

use Tavro\Model\EntityInterface\EntityInterface;

/**
 * Interface S3EntityHandlerInterface
 *
 * @package Tavro\Model\Handler\EntityInterface
 */
interface S3EntityHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Model\EntityInterface\EntityInterface $entity
     *
     * @return mixed
     */
    public function remove(Request $request, EntityInterface $entity);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     * @param $filename
     * @param string $directory
     *
     * @return mixed
     */
    public function move(Request $request, array $data, $filename, $directory = '');

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string $directory
     *
     * @return mixed
     */
    public function upload(Request $request, UploadedFile $file, $directory = '');
}
