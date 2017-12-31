<?php

namespace App\Handler\AccountEntity;

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

use App\Entity\Expense;

use App\Handler\AccountEntityHandler;
use App\Model\HandlerInterface\AccountEntityHandlerInterface;
use App\Model\HandlerInterface\CommentEntityHandlerInterface;
use App\Model\HandlerInterface\TagEntityHandlerInterface;

/**
 * Class ExpenseHandler
 *
 * @package Tavro\Handler\Entity
 */
class ExpenseHandler extends AccountEntityHandler implements CommentEntityHandlerInterface, TagEntityHandlerInterface, AccountEntityHandlerInterface
{

    /**
     * @param \App\Model\EntityInterface\EntityInterface $expense
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
     * @param \App\Model\EntityInterface\EntityInterface $expense
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