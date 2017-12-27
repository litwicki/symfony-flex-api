<?php

namespace Tavro\Bundle\CoreBundle\Handler\AccountEntity;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Handler\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
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

use Tavro\Bundle\CoreBundle\Entity\Expense;

use Tavro\Bundle\CoreBundle\Handler\AccountEntityHandler;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\AccountEntityHandlerInterface;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\CommentEntityHandlerInterface;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\TagEntityHandlerInterface;

/**
 * Class ExpenseHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class ExpenseHandler extends AccountEntityHandler implements CommentEntityHandlerInterface, TagEntityHandlerInterface, AccountEntityHandlerInterface
{

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $expense
     *
     * @return array
     * @throws \Exception
     */
    public function getComments(EntityInterface $expense)
    {

        $comments = array();

        if(!$expense instanceof Expense) {
            throw new \Exception(sprintf('Cannot fetch comments for Expense from %s', get_class($expense)));
        }

        $entities = $expense->getExpenseComments();

        if(!empty($entities)) {
            foreach($entities as $entity) {
                $comments[$entity->getId()] = $entity->getComment();
            }
        }

        return $comments;

    }

    /**
     * Get All Tags.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $expense
     *
     * @return array
     * @throws \Exception
     */
    public function getTags(EntityInterface $expense)
    {

        $tags = array();

        if(!$expense instanceof Expense) {
            throw new \Exception(sprintf('Cannot fetch tags for Expense from %s', get_class($expense)));
        }

        $entities = $expense->getExpenseTags();

        if(!empty($entities)) {
            foreach($entities as $entity) {
                $tags[$entity->getId()] = $entity->getTag();
            }
        }

        return $tags;

    }

}