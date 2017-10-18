<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Exceptions;

/**
 * Thrown on serious security-related events - you should NEVER ignore that exception!
 */
class SecurityViolationException extends \RuntimeException
{
    public function __construct(
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $newMessage = 'Security violation occurred, operation was aborted.';

        if (!empty($message)) {
            $newMessage .= ' ' . $message;
        }

        parent::__construct($newMessage, $code, $previous);
    }
}
