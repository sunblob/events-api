<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class ForbiddenException extends HttpException
{
    public function __construct(string $message)
    {
        parent::__construct(403, $message);
    }
} 