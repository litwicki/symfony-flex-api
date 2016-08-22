<?php

namespace Tavro\Bundle\CoreBundle\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordComplexityValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {

        $specials = '/[!@#$%^&*()\-_=+]/';  // whatever you mean by 'special char'
        $numbers = '/[0-9]/';  //numbers
        $errors = array();

        if (preg_match_all($specials, $value, $o) < 1) {
            $errors[] = sprintf(
                'Password "%s" does not contain at least one special character: %s',
                $value,
                str_replace('/', '', $specials)
            );
        }

        if (preg_match_all($numbers, $value, $o) < 1) {
            $errors[] = sprintf(
                'Password "%s" does not contain a number.',
                $value
            );
        }

        if (strlen($value) < 8) {
            $errors[] = sprintf('Your password must be at least 8 characters long.');
        }

        if (preg_match("/\\s/", $value)) {
            $errors[] = sprintf('Your password cannot contain spaces.');
        }
        
        if(!empty($errors))
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
        
    }

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}