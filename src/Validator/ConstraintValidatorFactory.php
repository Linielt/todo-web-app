<?php

namespace App\Validator;

use Symfony\Component\Validator\ConstraintValidatorFactory as SymfonyConstraintValidatorFactory;

class ConstraintValidatorFactory extends SymfonyConstraintValidatorFactory
{
    public function addValidator($className, $validator)
    {
        $this->validators[$className] = $validator;
    }
}