<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
  public function __construct(string $message = 'Not found', int $code = 404)
  {
    parent::__construct($message, $code);
  }
}