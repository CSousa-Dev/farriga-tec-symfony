<?php 
namespace App\Domain\Validations;

class ErrorsList 
{
    private array $errors = [];
    public function __construct(public readonly string $field, public readonly ?string $group = null)
    {}

    public function addError(string $errorMessage)
    {
        $this->errors[] = $errorMessage;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}