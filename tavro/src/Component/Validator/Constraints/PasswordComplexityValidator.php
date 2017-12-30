<?php

namespace Tavro\Component\Validator\Constraints;

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
        $specials = '/[!@#$%^&*()\-_=+]/';
        $digits = '/[0-9]/';
        $letters = '/[a-zA-Z]/';
        $errors = [];

        if(!strlen($value)) {
            return;
        }

        if (!preg_match($specials, $value)) {
            $errors[] = 'The password does not contain at least one of the following special characters (!@#$%^&*()\-_=+).';
        }

        if (!preg_match($digits, $value)) {
            $errors[] = 'The password does not contain at least one digit.';
        }

        if (!preg_match($letters, $value)) {
            $errors[] = 'The password does not contain at least one letter.';
        }

        if (strlen($value) < 8) {
            $errors[] = 'The password must be at least 8 characters long.';
        }

        if (preg_match("/\\s/", $value)) {
            $errors[] = 'The password cannot contain any spaces.';
        }
        
        if(!empty($errors)) {
            $this->context->buildViolation(implode($errors, ' '))
                ->setParameter('%string%', $value)
                ->addViolation();
        }
        
    }

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}