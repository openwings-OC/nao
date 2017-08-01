<?php

namespace AppBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckImageSize extends Constraint{
    public $message = "Votre image ne doit pas dépasser 20 Mo";

    public function validatedBy()
    {
        return 'app_check_size_image';
    }
}