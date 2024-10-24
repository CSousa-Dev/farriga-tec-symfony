<?php
namespace App\Domain\Account\User\ValidationRules;

use DateTime;
use App\Domain\Validations\Validator;
use App\Domain\Validations\IConstraints;
use App\Domain\Validations\ValidationList;
use App\Domain\Validations\ValidationRule;
use App\Domain\Validations\ValidationMaker;
use App\Domain\Validations\ValidationResult;

class PlainTextPasswordValidation extends ValidationMaker
{
    private IConstraints $constraints;
    private Validator $validator;

    public function __construct(IConstraints $constraints, Validator $validator)
    {
        $this->constraints = $constraints;
        $this->validator = $validator;
    }

    public function validatePassword(string $password, $firstName = null, $lastName = null, ?DateTime $birthDate = new DateTime('now')): ValidationResult
    {
        return $this->validator->validate($this->foreachRuleSetValue($password, ...$this->passwordSecurityRules($firstName, $lastName, $birthDate)));
    }

    public function validateConfirmation(string $password, string $confirmPassword): ValidationResult
    {
        $isEqual = $password === $confirmPassword;
        return $this->validator->validate($this->foreachRuleSetValue($this->confirmationPasswordRules()->setValue($isEqual)));
    }

    private function confirmationPasswordRules(): ValidationRule
    {
        $confirmationPassword = new ValidationRule(
            validationRule: $this->constraints->isTrue(),
            field: 'password',
            message: 'As senhas não conferem.'
        );
        
        return $confirmationPassword;
    }

    private function passwordSecurityRules($firstName = null, $lastName = null, ?DateTime $birthDate = new DateTime()): array
    {
        if($birthDate === null) $birthDate = new DateTime('now');

        $especialChar = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) {
                    if(!self::hasSafeSpecialCharacter($password)) return 'Deve conter pelo menos um caractere especial. Ex: !@#$%^&*().';

                    return null;
                }
            ),
            field: 'password',
            message: 'Deve conter pelo menos um caractere especial. Ex: !@#$%^&*().'
        );

        $lowerCase = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) {
                    if(!self::hasLowerCaseLetter($password)) return 'Deve conter pelo menos uma letra minúscula.';
                    
                    return null;
                }
            ),
            field: 'password',
            message: 'Deve conter pelo menos uma letra minúscula.'
        );

        $sequential = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) {
                    $matches = self::numbersSequencesOrLettersSequencesFound($password);

                    if($matches) return 'Não pode conter sequências óbvias de números ou letras. Sequencia(s) encontrada(s): ' . implode(', ', $matches);

                    return null;
                }
            ),
            field: 'password',
            message: 'Não pode conter sequências de números ou letras.'
        );

        $consecutive = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) {
                    if(!self::noMoreThanTwoConsecutiveCharacters($password)) return 'Não pode conter mais de dois caracteres iguais consecutivos.';

                    return null;
                }
            ),
            field: 'password',
            message: 'Não pode conter mais de dois caracteres iguais consecutivos.'
        );

        $upperCase = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) {
                    if(!self::hasUpperCaseLetter($password)) return 'Deve conter pelo menos uma letra maiúscula.';

                    return null;
                }
            ),
            field: 'password',
            message: 'Deve conter pelo menos uma letra maiúscula.'
        );

        $minEightChars = new ValidationRule(
            validationRule: $this->constraints->length(
                min: 8,
            ),
            field: 'password',
            message: 'A senha deve conter no mínimo 8 caracteres.',
            messageType: 'minMessage'
        );

        $mustNotContainBirthdate = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) use ($birthDate) {
                    if(self::checkPasswordContainADate($password, $birthDate)) return 'Não pode conter sua data de nascimento.';

                    return null;
                }
            ),
            field: 'password',
            message: 'Não pode conter sua data de nascimento.'
        );

        $mustNotContainFirstName = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) use ($firstName) {

                    if($firstName === null) return null;

                    if(stripos(strtoupper($password), strtoupper($firstName)) !== false) return 'Não pode conter seu nome.';

                    return null;
                }
            ),
            field: 'password',
            message: 'Não pode conter seu nome.'
        );

        $mustNotContainLastName = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) use ($lastName) {

                    if($lastName === null) return null;

                    if(stripos(strtoupper($password), strtoupper($lastName)) !== false) return 'Não pode conter seu sobrenome.';

                    return null;
                }
            ),
            field: 'password',
            message: 'Não pode conter seu sobrenome na senha.'
        );

        $needAtLeastOneNumber = new ValidationRule(
            validationRule: $this->constraints->callback(
                function ($password) {
                    if(!preg_match('/[0-9]/', $password)) return 'Deve conter pelo menos um número.';

                    return null;
                }
            ),
            field: 'password',
            message: 'Deve conter pelo menos um número.'
        );

        return [
            $needAtLeastOneNumber,
            $especialChar,
            $lowerCase,
            $sequential,
            $consecutive, 
            $upperCase,
            $minEightChars,
            $mustNotContainBirthdate,
            $mustNotContainFirstName,
            $mustNotContainLastName
        ];
        
    }

    private static function hasSafeSpecialCharacter($password) {
        // Lista de caracteres considerados seguros
        $safeCharacters = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '{', '}', ';', ':', ',','.'];
        
        foreach ($safeCharacters as $char) {
            if (strpos($password, $char) !== false) {
                return true;
            }
        }
        
        return false;
    }
      
    private static function hasLowerCaseLetter($password) {
        return preg_match('/[a-z]/', $password);
    }

    private static function numbersSequencesOrLettersSequencesFound($password) {
        $sequences = [
            'abc', 'bcd', 'cde', 'def', 'efg', 'fgh', 'ghi', 'hij', 'ijk', 'jkl',
            'klm', 'lmn', 'mno', 'nop', 'opq', 'pqr', 'qrs', 'rst', 'stu', 'tuv',
            'uvw', 'vwx', 'wxy', 'xyz', 'zyx', 'yxw', 'xwv', 'wvu', 'vut', 'uts',
            'tsr', 'srq', 'rqp', 'qpo', 'pon', 'onm', 'nml', 'mlk', 'lkj', 'kji',
            'jih', 'ihg', 'hgf', 'gfe', 'fed', 'edc', 'dcb', 'cba',
            '123', '234', '345', '456', '567', '678', '789', '987', '876', '765',
            '654', '543', '432', '321', '210'
        ];

        $matches = [];
        foreach ($sequences as $sequence) {
            if (strpos(strtoupper($password), strtoupper($sequence)) !== false) {
                $matches[] = $sequence;
            }
        }

        return $matches;
    }
        
    private static function noMoreThanTwoConsecutiveCharacters($password) {
        return !preg_match('/(.)\1\1/', $password);
    }

    private static function hasUpperCaseLetter($password) {
        return preg_match('/[A-Z]/', $password);
    }

    
    private static function checkPasswordContainADate($password, DateTime $date)
    {
        $dateYmd = $date->format('Ymd');
        $dateDmY = $date->format('dmY');
        
        $regexYmd = "/$dateYmd/";
        $regexDmY = "/$dateDmY/";

        
        if (preg_match($regexYmd, $password) || preg_match($regexDmY, $password)) {
            return true; 
        }
        
        return false; 
    }

    public function allRules(): ValidationList
    {
        return new ValidationList(...$this->passwordSecurityRules());
    }
}