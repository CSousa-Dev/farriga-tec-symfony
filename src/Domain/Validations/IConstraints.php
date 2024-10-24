<?php 
namespace App\Domain\Validations;

interface IConstraints
{
    public function email();
    public function regex(string | array | null $pattern, bool | null $match = true);
    public function range($min, $max);

    public function length(?int $min = null, ?int $max = null);

    public function notNull();
    public function notBlanck();

    /**
     * The callback receives as a parameter any value passed within the 'value' field of the validationRule. At the end of its execution, it should return a string containing the error message to be thrown or an array of strings containing multiple error messages. If there is no error, it should return null.
     *
     * @param callable(mixed $value): string | string[] | null $callback
     */
    public function callback(callable $callback);

    public function isDate();

    public function greaterThanOrEqual($value);
    public function greaterThan($value);
    public function lessThanOrEqual($value);

    public function isTrue();
}