<?php
namespace App\Service\Validation\Document;

use App\Domain\Account\Documents\ValidationRules\CpfValidaton;
use App\Domain\Account\Documents\ValidationRules\DocumentValidationRulesInterface;

class AllValidationForDocuments implements DocumentValidationRulesInterface
{
    public function __construct(
        private CpfValidaton $cpfValidaton
    ){}

    public function allDocumentsValidations()
    {
        return [
            $this->cpfValidaton
        ];
    }

}