<?php 
namespace App\Service\Validation;

use App\Domain\Validations\IConstraints;
use App\Service\Validation\SymfonyValidationConstraints;

class SymfonyConstraints extends SymfonyValidationConstraints implements IConstraints
{
}