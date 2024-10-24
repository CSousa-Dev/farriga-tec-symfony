<?php
namespace App\Application;

use Exception;

class NotConfirmedEmailException extends Exception
{
    public function __construct(
        public $message = "Email not confirmed.", 
        private $token = null
    )
    {
        parent::__construct(message: $message, code: 401);
    }

    public function authToken()
    {
        return $this->token;
    }
}