<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Exceptions;

class EmptyPathException extends \DomainException
{
    public static function withDefaultMessage(): self
    {
        return new self("Path can't be empty.");
    }
}
