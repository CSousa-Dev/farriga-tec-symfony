<?php
namespace App\Domain\Account\Documents\ValidationRules;

use App\Domain\Validations\ValidationRule;

interface ValidationsForDocuments
{
    public function typeMatchesMyValidationType($documentType): bool;

    public function type(): string;

    /**
     * @return ValidationRule[]
     */
    public function rules();

    public function optionsToValue($options);

}