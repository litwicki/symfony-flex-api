<?php
namespace Tavro\Bundle\CoreBundle\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordComplexityConstraint extends Constraint
{
    public $message = 'This password does not meet the minimum complexity requirements!';
}