<?php
namespace App\Exceptions;

use \Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    protected $errors = [];

    public function addError(string $code, string $message): ApiException
    {
        $this->errors[] = [
            'code' => $code,
            'message' => $message,
        ];
        return $this;
    }

    public function errors()
    {
        return $this->errors;
    }
}
