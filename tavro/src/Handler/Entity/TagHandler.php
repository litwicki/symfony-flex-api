<?php

namespace App\Handler\Entity;

use App\Exception\Api\ApiException;
use App\Handler\EntityHandler;
use App\Exception\Form\InvalidFormException;
use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\UsernameNotUniqueException;
use App\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use App\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TagHandler
 *
 * @package Tavro\Handler\Entity
 */
class TagHandler extends EntityHandler
{

    /**
     * @param array $parameters
     *
     * @return array
     * @throws \Exception
     */
    public function typeahead(array $parameters)
    {
        try {

            if(isset($parameters['title'])) {

                $sql = 'SELECT t FROM TavroCoreBundle:Tag t WHERE t.title LIKE :title';
                $query = $this->om->createQuery($sql);

                $query->setParameter('title', '%' . $parameters['title'] . '%');

                $entities = $query->getResult();

            }
            else {
                $entities = $this->getRepository()->findAll();
            }

            return $entities;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
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
            $options = array('status', 'title');

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
     * @param $tag
     *
     * @return object
     * @throws \Exception
     */
    public function findByTag($tag)
    {
        try {

            $tag = $this->getRepository()->findOneBy(array(
                'tag' => $tag,
            ));

            return $tag;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}