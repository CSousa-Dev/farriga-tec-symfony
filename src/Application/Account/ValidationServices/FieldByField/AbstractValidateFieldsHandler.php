<?php
namespace App\Application\Account\ValidationServices\FieldByField;

use App\Domain\Validations\ValidationResult;
use App\Application\Account\ValidationServices\FieldByField\InvalidValidationServiceNameException;
use App\Application\Account\ValidationServices\FieldByField\IndispobileFieldForValidationServiceException;

abstract class AbstractValidateFieldsHandler
{
    private ?AbstractValidateFieldsHandler $nextHandler = null;
    public abstract function serviceName(): string;
    public abstract function disponibleFields(): array;
    public abstract function exec(array $fields): ValidationResult;

    public function setNextHandler(AbstractValidateFieldsHandler $handler): AbstractValidateFieldsHandler
    {
        $this->nextHandler = $handler;
        return $this;
    }

    public function handle(string $serviceName, array $fields): ValidationResult 
    {
        if ($this->canHandle($serviceName)) {
            $this->hasIndisponibleFields($fields);
            return $this->exec($fields);
        } 

        if(!$this->canHandle($serviceName) && $this->nextHandler === null) throw new InvalidValidationServiceNameException($serviceName, $this->serviceName());
        
        return $this->nextHandler->handle($serviceName, $fields);
    }

    private function canHandle(string $serviceName): bool
    {
        return strtoupper($this->serviceName()) === strtoupper($serviceName);
    }

    private function hasIndisponibleFields(array $fields)
    {
        $indisponibleFields = [];
        $disponibleFields = $this->disponibleFields();
        foreach ($fields as $field => $value) {
            if (!in_array($field, $disponibleFields)) {
                $indisponibleFields[] = $field;
            }
        }
        
        if(count($indisponibleFields) > 0) {
            throw new IndispobileFieldForValidationServiceException($indisponibleFields ,$this->serviceName());
        }
    }
}