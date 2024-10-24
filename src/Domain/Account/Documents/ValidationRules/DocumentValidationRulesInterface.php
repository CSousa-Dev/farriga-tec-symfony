<?php 
namespace App\Domain\Account\Documents\ValidationRules;

use App\Domain\Account\Documents\ValidationRules\ValidationsForDocuments;

interface DocumentValidationRulesInterface 
{
    /**
     * @return ValidationsForDocuments[]
     */
    public function allDocumentsValidations();
}