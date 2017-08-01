<?php

namespace AppBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckImageType extends Constraint{

    public $message = "Votre image doit être de format jpeg ou png";

    public function validatedBy()
    {
        return 'app_check_type_image';
    }
}