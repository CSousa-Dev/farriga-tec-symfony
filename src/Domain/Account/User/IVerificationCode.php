<?php
namespace App\Domain\Account\User;

interface IVerificationCode
{
    public function __construct($lastestCode);
    public function checkCode(string $code): bool;
    public function generateCode(): string;
    public function lastestCode(): string;
}