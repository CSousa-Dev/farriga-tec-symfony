<?php 
namespace App\Domain\Account\Documents\ValidationRules;
use App\Domain\Validations\IConstraints;
use App\Domain\Validations\ValidationRule;
use App\Domain\Account\Documents\ValidationRules\ValidationsForDocuments;

class CpfValidaton implements ValidationsForDocuments 
{
    public function type(): string
    {
        return 'CPF';
    }
    
    public function __construct(private IConstraints $constraints) {}
    
    public function typeMatchesMyValidationType($documentType): bool {
        return strtoupper($documentType) == $this->type();
    }

    public function optionsToValue($options)
    {
        if(!is_string($options) && !is_numeric($options) && !is_null($options) ) 
            throw new \InvalidArgumentException("Options for CpfValidator must be a string or an integer or null");

        return $this->removeSlashesAndDashesAndDots($options);
    }

    public function rules() {

        $isRequired = new ValidationRule(
            validationRule: $this->constraints->notBlanck(),
            message: "É obrigatório.",
            field: "document"
        );

        $mustContainOnlyNumbers = new ValidationRule(
            validationRule: $this->constraints->callback( 
                callback: function ($cpf) {
                    if(!$this->containsOnlyNumbers($cpf)) return "Deve conter apenas números.";
                }
            ),
            message: "Deve conter apenas números.",
            field: "document"
        );

        $mustContain11Characters = new ValidationRule(
            validationRule: $this->constraints->callback( 
                callback: function ($cpf) {
                    if(!$this->contains11Characters($cpf)) return "Deve conter 11 caracteres.";
                }
            ),
            message: "Deve conter 11 caracteres.",
            field: "document"
        );

        $mustNotContainAllNumbersEqual = new ValidationRule(
            validationRule: $this->constraints->callback( 
                callback: function ($cpf) {
                    if($this->allNumbersAreEqual($cpf)) return "Não pode conter todos os números iguais.";
                }
            ),
            message: "Não pode conter todos os números iguais.",
            field: "document"
        );

        $mustPassDigitRules = new ValidationRule(
            validationRule: $this->constraints->callback( 
                callback: function ($cpf) {
                    if(!$this->digitsPassDigitRules($cpf)) return "Digitos inválidos.";
                }
            ),
            message: "Digitos inválidos.",
            field: "document"
        );

        
        return [
            $isRequired,
            $mustContainOnlyNumbers,
            $mustContain11Characters,
            $mustNotContainAllNumbersEqual,
            $mustPassDigitRules
        ];
    }

    private function containsOnlyNumbers($cpf) {
        return preg_match('/^[0-9]*$/', $cpf);
    }

    private function contains11Characters($cpf) {
        return strlen($cpf) == 11;
    }

    private function allNumbersAreEqual($cpf) {
        return preg_match('/(\d)\1{10}/', $cpf);
    }

    private function removeSlashesAndDashesAndDots($cpf) {
        if(is_null($cpf)) return null;
        return preg_replace('/[-\/\.]/', '', $cpf);
    }

    private function digitsPassDigitRules($cpf) {

        $cpf = $this->removeSlashesAndDashesAndDots($cpf);

        if(!$this->contains11Characters($cpf)) return false;
        if(!$this->containsOnlyNumbers($cpf)) return false;
        if($this->allNumbersAreEqual($cpf)) return false;

        $cpf = (string)$cpf;

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;   
    }
}