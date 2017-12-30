<?php

namespace Tavro\Handler\AccountEntity;

use Tavro\Exception\Api\ApiException;
use Tavro\Handler\EntityHandler;
use Tavro\Exception\Form\InvalidFormException;
use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\UsernameNotUniqueException;
use Tavro\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

use Tavro\Entity\Expense;

use Tavro\Handler\AccountEntityHandler;
use Tavro\Model\HandlerInterface\AccountEntityHandlerInterface;
use Tavro\Model\HandlerInterface\CommentEntityHandlerInterface;
use Tavro\Model\HandlerInterface\TagEntityHandlerInterface;

/**
 * Class ExpenseHandler
 *
 * @package Tavro\Handler\Entity
 */
class ExpenseHandler extends AccountEntityHandler implements CommentEntityHandlerInterface, TagEntityHandlerInterface, AccountEntityHandlerInterface
{

    /**
     * @param \Tavro\Model\EntityInterface\EntityInterface $expense
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
     * @param \Tavro\Model\EntityInterface\EntityInterface $expense
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