<?php
namespace App\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Phone extends Constraint
{
    public $message = 'Invalid Phone Number';

}