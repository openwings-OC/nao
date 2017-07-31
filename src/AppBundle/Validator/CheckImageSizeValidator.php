<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckImageSizeValidator extends ConstraintValidator {

    public function validate ($value, Constraint $constraint){
        if($value !== null){
            if($value->getFile() !== null) {
                if ($value->getFile()->getSize() > 20971520) {
                    $this->context->addViolation($constraint->message);
                }
            }
            return;
        }
    }
}
