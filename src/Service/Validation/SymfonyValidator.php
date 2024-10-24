<?php
namespace App\Service\Validation;

use App\Domain\Validations\ErrorsList;
use App\Domain\Validations\Validator;
use App\Domain\Validations\ValidationRule;
use Symfony\Component\Validator\ValidatorBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SymfonyValidator extends Validator
{
    public function validateRule(ValidationRule $validationRule): ErrorsList
    {

        $value       = $validationRule->value();
        $group       = $validationRule->group;
        $constraint  = $validationRule->validationRule();
        $messageType = $validationRule->messageType;
        
        if (property_exists($constraint , $messageType)) {
            $constraint->$messageType = $validationRule->message;
        }

        $validator = (new ValidatorBuilder())->getValidator();
        $violations = $validator->validate($value, $constraint);

        
        $errorsList = new ErrorsList($validationRule->field, $group);

        if (count($violations) > 0) {
            foreach($violations as $violation) {
                $errorsList->addError($violation->getMessage());
            }
        }

        return $errorsList;
    }
}