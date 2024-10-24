<?php 
namespace App\Domain\Validations;

class ValidationResult 
{
    private array $errors = [];
    private array $validatedFields = [];

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function addValidatedField(string $field): void
    {
        if (!in_array($field, $this->validatedFields, true)) {
            $this->validatedFields[] = $field;
        }
    }

    public function validatedFields(): array
    {
        return $this->validatedFields;
    }

    public function addError(string $field, string $error, ?string $group = null): void
    {
        if($group !== null)
            $this->errors[$group][$field][] = $error;
        
        if($group === null)
            $this->errors[$field][] = $error;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function addAnotherValidationResult(ValidationResult $validationResult): void
    {
        foreach ($validationResult->errors() as $fieldNameOrGroup => $errorsOrFields) {
            foreach ($errorsOrFields as $indexOrFieldName => $errorOrErrors) {
                
                $isGroup = is_array($errorOrErrors);

                if($isGroup){
                    $group = $fieldNameOrGroup;
                    foreach($errorOrErrors as $error){
                        $this->addError($indexOrFieldName, $error, $group);
                    }
                    continue;
                }

                $fieldName = $fieldNameOrGroup;
                $error = $errorOrErrors;
                $this->addError($fieldName, $error);
            }
        }

        foreach ($validationResult->validatedFields() as $field) {
            $this->addValidatedField($field);
        }
    }
}