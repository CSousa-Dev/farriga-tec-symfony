<?php 
namespace App\Domain\Account\Documents;

use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationResult;
use App\Domain\Account\Documents\ValidationRules\DocumentValidation;
use App\Domain\Validations\Validable;

class Document extends Validable implements DocumentInterface 
{
    public function __construct(private DocumentValidation $documentValidator, private readonly string $number, private readonly string $type)
    {}
    
    public function number(): string
    {
        return $this->number;
    }


    public function type(): string
    {
        return $this->type;
    }

    public function validate(): ValidationResult
    {
        return $this->documentValidator->validate($this->type, $this->number);
    }   

    public function validationRules(): ValidationList
    {
        return $this->documentValidator->allRules();
    }
}