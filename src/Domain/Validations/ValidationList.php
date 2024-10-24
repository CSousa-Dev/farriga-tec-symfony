<?php
namespace App\Domain\Validations;

class ValidationList 
{
    /**
     * @return ValidationRule[]
     */
    private array $validations;


    public function __construct(?ValidationRule ...$validations)
    {
        $this->validations = $validations;
    }

    /**
     * @return ValidationRule[]
     */
    public function validations(): array
    {
        return $this->validations;
    }

    public function addRule(ValidationRule $rule): void
    {
        $this->validations[] = $rule;
    }

    public function addMultipleRules(ValidationRule ...$rules): void
    {
        foreach($rules as $rule) {
            $this->addRule($rule);
        }
    }
}