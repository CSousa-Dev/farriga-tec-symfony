<?php
namespace App\Domain\Validations;

class ValidationRule
{ 
    public function __construct(
        private $validationRule, 
        public readonly ?string $message, 
        public readonly ?string $messageType = 'message',
        private mixed $value = null, 
        public readonly ?string $field = null, 
        public readonly ?string $group = null)
    {}

    public function setValue(mixed $value)
    {
        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }

    public function validationRule()
    {
        return $this->validationRule;
    }
}