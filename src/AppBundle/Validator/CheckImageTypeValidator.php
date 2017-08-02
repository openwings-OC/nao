<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckImageTypeValidator extends ConstraintValidator {

    public function validate ($value, Constraint $constraint){
        if($value !== null){
            if($value->getFile() !== null){
                if($value->getFile()->getmimeType() !== 'image/png' && $value->getFile()->getmimeType() !== 'image/jpeg'){
                   $this->context->addViolation($constraint->message);
                }
            }
        }
        return;
    }
}
