<?php 
namespace App\Service\Validation;
use App\Domain\Validations\IConstraints;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SymfonyValidationConstraints implements IConstraints
{
    public function email()
    {
        return new Assert\Email();
    }

    public function notNull()
    {
        return new Assert\NotNull();
    }

    public function notBlanck()
    {
        return new Assert\NotBlank();
    }

    public function isDate()
    {
        return new Assert\Date();
    }

    /**
     * Undocumented function
     *
     * @param callable $callback Contém a callback que será executada cuja sua função é retornar a mensagem um array de mensagens de erro, ou null caso não haja erro.
     * @param [type] $message
     */
    public function callback(callable $callback)
    {
        return new Assert\Callback(
            function ($value, ExecutionContextInterface $context) use ($callback) {
                $errors = $callback($value);
                
                if (is_null($errors)) {
                    return;
                }

                if(is_string($errors)) {
                    $errors = [$errors];
                }

                foreach($errors as $errorMessage) {
                    $context->buildViolation($errorMessage)->addViolation();
                }
            }
        );
    }

    public function length(int|null $min = null, int|null $max = null, string|null $minMessage = null, string|null $maxMessage = null)
    {
        return new Assert\Length(
            min: $min,
            max: $max
        );
    }

    public function greaterThan($value)
    {
        return new Assert\GreaterThan(
            value: $value
        );
    }

    public function greaterThanOrEqual($value)
    {
        return new Assert\GreaterThanOrEqual(
            value: $value
        );
    }

    public function lessThanOrEqual($value)
    {
        return new Assert\LessThanOrEqual(
            value: $value
        );
    }

    public function range($min, $max)
    {
        return new Assert\Range(
            min: $min,
            max: $max
        );
    }

    public function regex($pattern, $match = true)
    {
        return new Assert\Regex(
            pattern: $pattern,
            match: $match,
        );
    }

    public function isTrue()
    {
        return new Assert\IsTrue();
    }
}

            