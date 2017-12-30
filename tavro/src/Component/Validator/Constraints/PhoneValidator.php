<?php

namespace Tavro\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $regex = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
        $errors = [];

        if(!strlen($value)) {
            return;
        }

        if (!preg_match($regex, $value)) {
            $errors[] = 'Improperly formatted Phone Number';
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