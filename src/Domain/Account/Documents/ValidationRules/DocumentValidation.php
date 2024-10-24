<?php
namespace App\Domain\Account\Documents\ValidationRules;

use App\Domain\Account\Documents\ValidationRules\ValidationsForDocuments;
use App\Domain\Validations\Validator;
use App\Domain\Validations\IConstraints;
use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationRule;
use App\Domain\Validations\ValidationMaker;
use App\Domain\Validations\ValidationResult;

class DocumentValidation extends ValidationMaker
{

    /**
     * @var ValidationsForDocuments[]
     */
    private $validationsForDocuments;

    /**
     *
     * @var ?ValidationRule[]
     */
    private $validationRules = null;

    /**
     *
     * @var ValidationsForDocuments
     */
    private ?ValidationsForDocuments $matchValidationForDocument = null;

    public function __construct(private IConstraints $constraints, private Validator $validator, DocumentValidationRulesInterface $documentValidationRules)
    {
        $this->validationsForDocuments = $documentValidationRules->allDocumentsValidations();
    }

    public function validate($documentType, $options): ValidationResult
    {
        $this->getValidationType($documentType);

        if($this->matchValidationForDocument === null) 
            return $this->validator->validate(
                new ValidationList(
                    $this->undefinedTypeRule()
                )
            );
        
        
        return $this->validator->validate(
            $this->foreachRuleSetValue(
                $this->matchValidationForDocument->optionsToValue($options), 
                ...$this->matchValidationForDocument->rules()
            )
        );
    }


    private function undefinedTypeRule(): ValidationRule
    {
        $rule = new ValidationRule(
            validationRule: $this->constraints->isTrue(), 
            message: 'Deve ser informado um document e com tipo válido.',
            field: 'document'
        );
        $rule->setValue(false);
        return $rule;
    }


    private function getValidationType($documentType) {
        foreach($this->validationsForDocuments as $validationForDocument) {
            if($validationForDocument->typeMatchesMyValidationType($documentType)) {
                $this->matchValidationForDocument = $validationForDocument; 
                break;
            }
        }
    }

    public function allRules(): ValidationList
    {
        $validationList = new ValidationList();
        foreach($this->validationsForDocuments as $validationForDocument) {
            $validationList->addMultipleRules(...$validationForDocument->rules());
        }

        return $validationList;
    }


}