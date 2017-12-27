<?php
namespace Tavro\Bundle\CoreBundle\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordComplexity extends Constraint
{
    public $message = 'User `password` must be at least 8 characters with at least one digit (0-9) and special character (!@#$%^&*()\-_=+), and contain no spaces.';

}