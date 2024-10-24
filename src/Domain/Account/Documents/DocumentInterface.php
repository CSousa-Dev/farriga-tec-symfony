<?php
namespace App\Domain\Account\Documents;
use App\Domain\Validations\Validable;

interface DocumentInterface 
{
    public function number(): string;
    public function type(): string;
}